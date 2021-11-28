<?php

namespace App\Http\Controllers\Api\HRD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivityController as Activity;
use App\User;
use App\Absensi;
use App\Izin;
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

        $data = [
            'total_absen_masuk'                 => $total_absen_masuk,
            'total_absen_masuk_tepat'           => $total_absen_masuk_tepat,
            'total_absen_masuk_tidak_tepat'     => $total_absen_masuk_tidak_tepat,
            'total_absen_pulang'                => $total_absen_pulang,
            'total_absen_pulang_tepat'          => $total_absen_pulang_tepat,
            'total_absen_pulang_tidak_tepat'    => $total_absen_pulang_tidak_tepat,
            'izin_sakit'                        => $izin_sakit,
            'izin_cuti_pending'                 => $izin_cuti_pending,
            'izin_cuti_approved'                => $izin_cuti_approved,
            'izin_cuti_rejected'                => $izin_cuti_rejected,
        ];

        $response = [
            'success' => true,
            'message' => "data available",
            'data'    => $data
        ];

        return response()->json($response);
    }

    public function updateNIP(Request $request)
    {
        DB::beginTransaction();
        try{
            $user = User::where('id', Auth::user()->id)->first();

            Activity::log(Auth::user()->id, 'Update', 'merubah NIP pengguna', 'Diperbarui menjadi ' . $request->nip, 'Nama sebelumnya ' . $user->nip, Carbon::now('Asia/Jakarta'));

            User::where('id', Auth::user()->id)->update([
                'nip' => $request->nip
            ]);

            DB::commit();

            $response = [
                'success' => true,
                'message' => "NIP Anda berhasil diubah",
                'data'    => []
            ];
        }catch (\Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => "NIP Anda gagal diubah",
                'data'    => []
            ];
        }

        return response()->json($response);
    }

    public function updateNama(Request $request)
    {
        DB::beginTransaction();
        try{
            $user = User::where('id', Auth::user()->id)->first();

            Activity::log(Auth::user()->id, 'Update', 'merubah nama pengguna', 'Diperbarui menjadi ' . $request->nama, 'Nama sebelumnya ' . $user->name, Carbon::now('Asia/Jakarta'));

            User::where('id', Auth::user()->id)->update([
                'name' => $request->nama
            ]);

            DB::commit();

            $response = [
                'success' => true,
                'message' => "Nama Anda berhasil diubah",
                'data'    => []
            ];
        }catch (\Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => "Nama Anda gagal diubah",
                'data'    => []
            ];
        }

        return response()->json($response);
    }

    public function updateEmail(Request $request)
    {
        DB::beginTransaction();
        try{
            $check = User::where('email', $request->email)->count();

            if ($check > 0) {
                $response = [
                    'success' => false,
                    'message' => "Gagal memperbarui email, email sudah digunakan",
                    'data'    => []
                ];
            } else {
                $user = User::where('id', Auth::user()->id)->first();

                Activity::log(Auth::user()->id, 'Update', 'merubah email', 'Diperbarui menjadi ' . $request->email, 'Email sebelumnya ' . $user->email, Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'email' => $request->email
                ]);

                DB::commit();

                $response = [
                    'success' => true,
                    'message' => "Email Anda berhasil diubah",
                    'data'    => []
                ];
            }
        }catch (\Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => "Email Anda gagal diubah",
                'data'    => []
            ];
        }

        return response()->json($response);
    }

    public function updateUsername(Request $request)
    {
        DB::beginTransaction();
        try{
            $check = User::where('username', $request->username)->count();

            if ($check > 0) {
                $response = [
                    'success' => false,
                    'message' => "Gagal memperbarui username, username sudah digunakan",
                    'data'    => []
                ];
            } else {
                $user = User::where('id', Auth::user()->id)->first();

                Activity::log(Auth::user()->id, 'Update', 'merubah username', 'Diperbarui menjadi ' . $request->username, 'Username sebelumnya ' . $user->username, Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'username' => $request->username
                ]);

                DB::commit();

                $response = [
                    'success' => true,
                    'message' => "Username Anda berhasil diubah",
                    'data'    => []
                ];
            }
        }catch (\Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => "Username Anda gagal diubah",
                'data'    => []
            ];
        }

        return response()->json($response);
    }

    public function updatePassword(Request $request)
    {
        DB::beginTransaction();
        try{
            if ($request->oldPassword == "" || $request->newPassword == "" || $request->vernewPassword == ""){
                $response = [
                    'success' => false,
                    'message' => "Lengkapi data",
                    'data'    => []
                ];
            }

            $pwd        = User::where('id', Auth::user()->id)->first();
            $check_pwd  = Hash::check($request->oldPassword, $pwd->password, [true]);

            if ($check_pwd == false){
                $response = [
                    'success' => false,
                    'message' => "Kata sandi tidak ditemukan",
                    'data'    => []
                ];
            }else if ($request->vernewPassword != $request->newPassword){
                $response = [
                    'success' => false,
                    'message' => "Konfirmasi kata sandi baru salah",
                    'data'    => []
                ];
            } else if ($check_pwd == true && $request->vernewPassword == $request->newPassword){
                Activity::log(Auth::user()->id, 'Update', 'merubah kata sandi', 'Kata sandi telah diperbarui', null, Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'password' => bcrypt($request->newPassword)
                ]);
                
                DB::commit();

                $response = [
                    'success' => true,
                    'message' => "Kata sandi Anda berhasil diubah",
                    'data'    => []
                ];
            }
        }catch (\Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => "Kata sandi Anda gagal diubah",
                'data'    => []
            ];
        }

        return response()->json($response);
    }

    public function updateTtl(Request $request)
    {
        if ($request->tempat == "" || $request->tanggal == "" || $request->bulan == "" || $request->tahun == "") {
            $response = [
                'success' => false,
                'message' => "Lengkapi data",
                'data'    => []
            ];
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

                $response = [
                    'success' => true,
                    'message' => "Tempat, tanggal lahir Anda berhasil diubah",
                    'data'    => []
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'success' => false,
                    'message' => "Tempat, tanggal lahir Anda gagal diubah",
                    'data'    => []
                ];
            }
        }

        return response()->json($response);
    }

    public function updateAlamat(Request $request)
    {
        if ($request->alamat == "") {
            $response = [
                'success' => false,
                'message' => "Lengkapi data",
                'data'    => []
            ];
        } else {
            DB::beginTransaction();
            try{
                $user = User::where('id', Auth::user()->id)->first();

                Activity::log(Auth::user()->id, 'Update', 'merubah alamat', 'Diperbarui menjadi ' . $request->alamat, 'Alamat sebelumnya ' . $user->address, Carbon::now('Asia/Jakarta'));

                User::where('id', Auth::user()->id)->update([
                    'address' => $request->alamat
                ]);

                DB::commit();

                $response = [
                    'success' => true,
                    'message' => "Alamat Anda berhasil diubah",
                    'data'    => []
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'success' => false,
                    'message' => "Alamat Anda gagal diubah",
                    'data'    => []
                ];
            }
        }

        return response()->json($response);
    }
}
