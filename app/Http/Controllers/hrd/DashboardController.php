<?php

namespace App\Http\Controllers\hrd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\ActivityController as Activity;
use App\User;
use App\Absensi;
use App\Izin;
use File;
use Auth;
use DB;
use Helper;
use Response;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        $total_absen_masuk              = Absensi::where('jenis_absen', 'IN')->count();
        $total_absen_masuk_tepat        = Absensi::where('jenis_absen', 'IN')->where('status', 'VALID')->count();
        $total_absen_masuk_tidak_tepat  = Absensi::where('jenis_absen', 'IN')->where('status', 'TIDAK VALID')->count();

        $total_absen_pulang             = Absensi::where('jenis_absen', 'OUT')->count();
        $total_absen_pulang_tepat       = Absensi::where('jenis_absen', 'OUT')->where('status', 'VALID')->count();
        $total_absen_pulang_tidak_tepat = Absensi::where('jenis_absen', 'OUT')->where('status', 'TIDAK VALID')->count();

        $izin_sakit         = Izin::where('jenis_izin', 'SAKIT')->count();
        $izin_cuti_pending  = Izin::where('jenis_izin', 'CUTI')->where('approve', 0)->count();
        $izin_cuti_approved = Izin::where('jenis_izin', 'CUTI')->where('approve', 1)->count();
        $izin_cuti_rejected = Izin::where('jenis_izin', 'CUTI')->where('approve', 2)->count();

        return view('hrd.dashboard.dashboard')->with(compact('total_absen_masuk', 'total_absen_pulang', 'total_absen_masuk_tepat', 'total_absen_masuk_tidak_tepat', 'total_absen_pulang_tepat', 'total_absen_pulang_tidak_tepat', 'izin_sakit', 'izin_cuti_pending', 'izin_cuti_approved', 'izin_cuti_rejected'));
    }

    public function profil()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        $tgllahir   = User::select('tgl_lahir')->where('id', Auth::user()->id)->first();
        $date       = [];

        if ($tgllahir->tgl_lahir != null) {
            $date = explode('-', $tgllahir->tgl_lahir);
            $day = $date[2];
            $month = $date[1];
            $year = $date[0];
        } else {
            $day = null;
            $month = null;
            $year = null;
        }

        return view('hrd.profile.index')->with(compact('day', 'month', 'year'));
    }

    public function updateNIP(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }
        
        DB::beginTransaction();
        try{
            $user = User::where('id', Auth::user()->id)->first();

            Activity::log(Auth::user()->id, 'Update', 'merubah NIP pengguna', 'Diperbarui menjadi ' . $request->nip, 'Nama sebelumnya ' . $user->nip, Carbon::now('Asia/Jakarta'));

            User::where('id', Auth::user()->id)->update([
                'nip' => $request->nip
            ]);

            DB::commit();

            return redirect()->back()->with('flash_message_success', 'NIP Anda berhasil diubah!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'NIP Anda gagal diubah!');
        }
    }

    public function updateNama(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        DB::beginTransaction();
        try{
            $user = User::where('id', Auth::user()->id)->first();

            Activity::log(Auth::user()->id, 'Update', 'merubah nama pengguna', 'Diperbarui menjadi ' . $request->nama, 'Nama sebelumnya ' . $user->name, Carbon::now('Asia/Jakarta'));

            User::where('id', Auth::user()->id)->update([
                'name' => $request->nama
            ]);

            DB::commit();

            return redirect()->back()->with('flash_message_success', 'Nama Anda berhasil diubah!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Nama Anda gagal diubah!');
        }
    }

    public function updateEmail(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        DB::beginTransaction();
        try{
            $check = User::where('email', $request->email)->count();

            if ($check > 0) {
                return redirect()->back()->with('flash_message_error', 'Gagal memperbarui email, email sudah digunakan!');
            } else {
                $user = User::where('id', Auth::user()->id)->first();

                Activity::log(Auth::user()->id, 'Update', 'merubah email', 'Diperbarui menjadi ' . $request->email, 'Email sebelumnya ' . $user->email, Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'email' => $request->email
                ]);

                DB::commit();

                return redirect()->back()->with('flash_message_success', 'Email Anda berhasil diubah!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Email Anda gagal diubah!');
        }
    }

    public function updateUsername(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        DB::beginTransaction();
        try{
            $check = User::where('username', $request->username)->count();

            if ($check > 0) {
                return redirect()->back()->with('flash_message_error', 'Gagal memperbarui username, username sudah digunakan!');
            } else {
                $user = User::where('id', Auth::user()->id)->first();

                Activity::log(Auth::user()->id, 'Update', 'merubah username', 'Diperbarui menjadi ' . $request->username, 'Username sebelumnya ' . $user->username, Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'username' => $request->username
                ]);

                DB::commit();

                return redirect()->back()->with('flash_message_success', 'Username Anda berhasil diubah!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Username Anda gagal diubah!');
        }
    }

    public function updatePassword(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        DB::beginTransaction();
        try{
            if ($request->oldPassword == "" || $request->newPassword == "" || $request->vernewPassword == ""){
                return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
            }

            $pwd        = User::where('id', Auth::user()->id)->first();
            $check_pwd  = Hash::check($request->oldPassword, $pwd->password, [true]);

            if ($check_pwd == false){
                return redirect()->back()->with('flash_message_error', 'Kata sandi tidak ditemukan!');
            }else if ($request->vernewPassword != $request->newPassword){
                return redirect()->back()->with('flash_message_error', 'Konfirmasi kata sandi baru salah!');
            } else if ($check_pwd == true && $request->vernewPassword == $request->newPassword){
                Activity::log(Auth::user()->id, 'Update', 'merubah kata sandi', 'Kata sandi telah diperbarui', null, Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'password' => bcrypt($request->newPassword)
                ]);

                DB::commit();

                return redirect()->back()->with('flash_message_success', 'Kata sandi Anda berhasil diubah!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Kata sandi Anda gagal diubah!');
        }
    }

    public function updateTtl(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        if ($request->tempat == "" || $request->tanggal == "" || $request->bulan == "" || $request->tahun == "") {
            return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
        } else {
            DB::beginTransaction();
            try{
                $tempat     = $request->tempat;
                $tgllahir   = $request->tahun . '-' . $request->bulan . '-' . $request->tanggal;
                $tgl        = $request->tanggal . '-' . $request->bulan . '-' . $request->tahun;
                $user       = User::where('id', Auth::user()->id)->first();

                Activity::log(Auth::user()->id, 'Update', 'merubah tempat, tanggal lahir', 'Diperbarui menjadi Tempat Lahir: '.$tempat.', Tanggal Lahir: '. $tgl, 'Tempat, Tanggal lahir sebelumnya Tempat Lahir: '.$user->tempat_lahir.', Tanggal Lahir: '. date('d-m-Y', strtotime($user->tgl_lahir)), Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'tempat_lahir' => $tempat,
                    'tgl_lahir' => $tgllahir
                ]);

                DB::commit();

                return redirect()->back()->with('flash_message_success', 'Tempat, tanggal lahir Anda berhasil diubah!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect()->back()->with('flash_message_error', 'Tempat, tanggal lahir Anda gagal diubah!');
            }
        }
    }

    public function updateAlamat(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        if ($request->alamat == "") {
            return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
        } else {
            DB::beginTransaction();
            try{
                $user = User::where('id', Auth::user()->id)->first();

                Activity::log(Auth::user()->id, 'Update', 'merubah alamat', 'Diperbarui menjadi ' . $request->alamat, 'Alamat sebelumnya ' . $user->address, Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'address' => $request->alamat
                ]);

                DB::commit();

                return redirect()->back()->with('flash_message_success', 'Alamat Anda berhasil diubah!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect()->back()->with('flash_message_error', 'Alamat Anda gagal diubah!');
            }
        }
    }

    public function updateFoto(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }
        
        DB::beginTransaction();
        try{

            if ($request->hasFile('foto')) {
                $image_tmp  = Input::file('foto');
                $file       = $request->file('foto');
                $image_size = $image_tmp->getSize(); //getClientSize()
                $maxsize    = '2097152';

                if ($image_size < $maxsize) {

                    if ($image_tmp->isValid()) {

                        $namefile = $request->current_img;

                        if ($namefile != "") {

                            $path = 'public/images/' . $namefile;

                            if (File::exists($path)) {
                                # code...
                                File::delete($path);
                            }

                        }

                        $extension  = $image_tmp->getClientOriginalExtension();
                        $filename   = date('YmdHms') . rand(111, 99999) . '.' . $extension;
                        $image_path = 'public/images';

                        if (!is_dir($image_path )) {
                            mkdir("public/images", 0777, true);
                        }

                        ini_set('memory_limit', '256M');

                        $file->move($image_path, $filename);

                        User::where('id', Auth::user()->id)->update(['img' => $filename]);

                        Activity::log(Auth::user()->id, 'Update', 'merubah foto profil', 'Foto profil telah diperbarui', null, Carbon::now('Asia/Jakarta'));

                        DB::commit();

                        return redirect()->back()->with('flash_message_success', 'Foto profil Anda berhasil diperbarui!');
                    }
                } else {
                    return redirect()->back()->with('flash_message_error', 'Foto profil gagal diperbarui...! Ukuran file terlalu besar');

                }
            }

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Foto profil Anda gagal diperbarui!');
        }
    }
}
