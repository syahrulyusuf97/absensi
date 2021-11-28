<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Mail;
use Helper;
use Jenssegers\Agent\Agent;
use App\Identitas;
use App\User;
use App\Syarat;
use App\Kebijakan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;

class SignController extends Controller
{

    private $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function login(Request $request) {
		if (Auth::check()) {
            if (Auth::user()->level == 1) {
                return redirect('/manager/dashboard');
            } else if (Auth::user()->level == 2) {
                return redirect('/dashboard');
            } else if (Auth::user()->level == 3) {
                return redirect('/hrd/dashboard');
            }
        }

    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->input();
    		if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>1], true)) {

                User::where('id', Auth::user()->id)->update([
                    'login' => Carbon::now('Asia/Jakarta')
                ]);

                Activity::log(Auth::user()->id, 'Login', 'Login', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

                if (Auth::user()->level == 1) {
                    return redirect('/manager/dashboard');
                } else if (Auth::user()->level == 2) {
                    return redirect('/dashboard');
                } else if (Auth::user()->level == 3) {
                    return redirect('/hrd/dashboard');
                } else {
                    return redirect('/logout');
                }
    		} else if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>0])) {

                $message = array(
                    'flash_message_error' => 'Akun Anda belum diaktivasi! hubungi admin untuk mengaktikan.'
                );
                Session::flush();
                Auth::logout();
                return redirect()->back()->with($message);
            } else if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>2])) {
                Session::flush();
                Auth::logout();
                return redirect()->back()->with('flash_message_error', 'Akun Anda sedang ditangguhkan, silahkan menghubungi Admin.');
            } else {
    			return redirect()->back()->with('flash_message_error', 'Username atau password salah');
    		}
    	} else {
            $identitas  = Identitas::first();
            return view('sign.login')->with(compact('identitas'));
        }
    }

    public function registrasi(Request $request) {
        if ($request->isMethod('post')) {
            # code...
            $data           = $request->input();
            $check_mail     = User::where('email', $data['email'])->count();
            $check_username = User::where('username', $data['username'])->count();
            if ($check_mail > 0) {
                return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran, email '.Helper::obfuscateEmail($data['email']).' sudah terdaftar!');
            } else if ($check_username > 0) {
                return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran, username <strong>"'.$data['username'].'"</strong> sudah terdaftar!');
            } else {
                DB::beginTransaction();
                try{
                    $user = DB::table('users')->insertGetId([
                        'name'          => ucwords($data['name']),
                        'sex'           => $data['sex'],
                        'email'         => $data['email'],
                        'username'      => $data['username'],
                        'password'      => bcrypt($data['password']),
                        'is_active'     => 1,//0:nonactive|1:active|2:suspend
                        'level'         => 2,//Karyawan
                        'created_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s'),
                        'updated_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s')
                    ]);
                    DB::commit();
                    
                    $message = array(
                        'flash_message_success' => 'Anda berhasil melakukan pendaftaran!'
                    );
                    return redirect('/registrasi')->with($message);                    
                }catch (\Exception $e){
                    DB::rollback();
                    return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran! COBA LAGI...!');
                }
            }
        } else {
            $identitas  = Identitas::first();
            return view('sign.registrasi')->with(compact('identitas'));
        }
    }

    public function resetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $email = User::where('email', $request->email);
            if ($email->count() > 0) {
                DB::beginTransaction();
                try{
                    DB::table('password_resets')->insert([
                        'email' => $request->email,
                        'token' => str_random(60),
                        'created_at' => Carbon::now('Asia/Jakarta')
                    ]);

                    $tokenData = DB::table('password_resets')
                        ->where('email', $request->email)->first();
                    Activity::log($email->first()->id, 'Login', 'permintaan reset password', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
                    DB::commit();
                    if ($this->sendResetEmail($request->email, $email->first()->username, $tokenData->token)) {
                        return redirect()->back()->with('flash_message_success', 'Tautan konfirmasi telah dikirim ke alamat email Anda '.Helper::obfuscateEmail($request->email).'. Silahkan cek kotak masuk email Anda atau folder spam. Apabila Anda tidak menerima email, silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini ');
                    } else {
                        return redirect()->back()->with('flash_message_error', 'A Network Error occurred. Please try again.');
                    }
                }catch(Exception $e){
                    DB::rollback();
                    return redirect()->back()->with('flash_message_error', 'A Network Error occurred. Please try again.');
                }
            } else {
                return redirect('/login')->with('flash_message_error', 'Email belum terdaftar!');
            }
        } else {
            $token = $request->get('token') ;
            $email = $request->get('email');

            if ($token == "" || $email == "") {
                return abort(404);
            }

            $check_token = DB::table('password_resets')->where('email', $request->get('email'))->where('token', $request->get('token'));
            if ($check_token->count() > 0) {
                return view('sign.resetPassword')->with(compact('token', 'email'));
            } else {
                return redirect('/login')->with('flash_message_error', 'Tidak ada permintaan perubahan kata sandi!');
            }
        }
    }

    public function passwordReset(Request $request)
    {
        if ($request->isMethod('post')) {
            $check_token = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token);
            
            if ($check_token->count() < 1) {
                return redirect('/login')->with('flash_message_error', 'Tidak ada permintaan perubahan kata sandi!');
            }

            $email = User::where('email', $request->email);

            if ($email->count() < 1) {
                return redirect('/login')->with('flash_message_error', 'Email tidak ditemukan!');
            }

            DB::beginTransaction();
            try{
                $email->update(['password'=>bcrypt($request->password)]);
                Activity::log($email->first()->id, 'Login', 'reset password', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
                DB::commit();
                if ($this->sendPWDEmail($email->first()->email, $email->first()->username, $request->password)) {
                    DB::table('password_resets')->where('email', $request->email)->delete();
                    return redirect('/login')->with('flash_message_success', 'Password berhasil diperbarui!');
                } else {
                    return redirect('/login')->with('flash_message_error', 'A Network Error occurred. Please try again.');
                }
            }catch(Exception $e){
                DB::rollback();
                return redirect('/login')->with('flash_message_error', 'A Network Error occurred. Please try again.');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Silahkan login untuk memulai sesi Anda');
        }
    }

    private function sendResetEmail($email, $username, $token)
    {
        $user = DB::table('users')->where('email', $email)->select('name', 'email')->first();
        $link = url('/reset_password') . '?token=' . $token . '&email=' . urlencode($user->email);
        try {
            Mail::to($user->email)->send(new \App\Mail\LinkResetPWD($link, $email, $username));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function sendPWDEmail($email, $username, $pwd)
    {
        try {
            Mail::to($email)->send(new \App\Mail\InfoPWD($email, $username, $pwd));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function logout() {
        if (Auth::check()) {
            User::where('id', Auth::user()->id)->update([
                'logout' => Carbon::now('Asia/Jakarta')
            ]);
            Activity::log(Auth::user()->id, 'Logout', 'Logout', 'IP Address: '. \Request::ip() . ' Device: '. \Request::header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
            Session::flush();
            Auth::logout();
            return redirect('/login')->with('flash_message_success', 'Logged out berhasil');
        } else {
            Session::flush();
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }
    }
}
