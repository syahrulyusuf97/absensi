<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Mail;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Validator;
use Helper;

class SignController extends Controller
{
    function randomNumber($length) {
        $result = '';
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

    public function login(Request $request) {

    	if ($request->isMethod('post')) {
    		# code...
            $validator = Validator::make($request->all(), [
                'username'      => 'required',
                'password'      => 'required',
                'remember'      => 'boolean'
            ]);

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors(),
                    'data' => []
                ];
            }

    		$data = $request->input();

            $user_exists = User::where('username', $data['username'])->exists();

            if ($user_exists) {
                if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>1, 'level'=>2])) {
                    # code...
                    $user = $request->user();
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    if ($request->remember) {
                        $token->expires_at = Carbon::now('Asia/Jakarta')->addWeeks(1);
                    } else {
                        $token->expires_at = Carbon::now('Asia/Jakarta')->addDays(1);
                    }
                    $token->save();

                    User::where('id', $user->id)->update([
                        'login_mobile' => Carbon::now('Asia/Jakarta')
                    ]);

                    Activity::log($user->id, 'Login', 'Login', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

                    $response = [
                        'success' => true,
                        'message' => 'User Found',
                        'error_code' => null,
                        'data' => [
                            'token_access'  => $tokenResult->accessToken,
                            'token_type'    => 'Bearer',
                            'expires_at'    => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
                        ]                    
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => Helper::errorCode(1208),
                        'data' => []
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => Helper::errorCode(1206),
                    'data' => []
                ];
            }
    	} else {
            $response = [
                'success' => false,
                'message' => Helper::errorCode(1106),
                'data' => []
            ];
        }

        return response()->json($response);
    }

    public function registrasi(Request $request) {
        if ($request->isMethod('post')) {
            # code...
            $validator = Validator::make($request->all(), [
                'nama'      => 'required',
                'jenis_kelamin' => 'required',
                'email'     => 'required|email',
                'username'  => 'required',
                'password'  => 'required'
            ]);

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors(),
                    'data' => []
                ];
                return response()->json($response);            
            }

            $data           = $request->input();
            $check_mail     = User::where('email', $data['email'])->count();
            $check_username = User::where('username', $data['username'])->count();
            $active_code    = $this->randomNumber(6);
            $code_expired   = date('Y-m-d H:i:s', strtotime('+1 day'));
            if ($check_mail > 0) {
                $response = [
                    'success' => false,
                    'message' => "Anda gagal melakukan pendaftaran, email sudah terdaftar!",
                    'data' => []
                ];
            } else if ($check_username > 0) {
                $response = [
                    'success' => false,
                    'message' => "Anda gagal melakukan pendaftaran, username sudah terdaftar!",
                    'data' => []
                ];
            } else {
                DB::beginTransaction();
                try{
                    $user = DB::table('users')->insertGetId([
                        'name'          => ucwords($data['nama']),
                        'sex'           => ($data['jenis_kelamin'] == 0 || is_null($data['jenis_kelamin'])) ? 'Laki-laki' : 'Perempuan',
                        'email'         => $data['email'],
                        'username'      => $data['username'],
                        'password'      => bcrypt($data['password']),
                        'is_active'     => 1,
                        'level'         => 2,
                        'created_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s'),
                        'updated_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s')
                    ]);
                    DB::commit();

                    $response = [
                        'success' => true,
                        'message' => "Anda berhasil melakukan pendaftaran",
                        'data' => []
                    ];                 
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success' => false,
                        'message' => "Anda gagal melakukan pendaftaran. Coba Lagi...!",
                        'data' => []
                    ];
                }
            }
            return response()->json($response);
        } else {
            $response = [
                'success' => false,
                'message' => 'Method Not Allowed',
                'data' => []
            ];
            return response()->json($response);
        }
    }

    public function logout(Request $request) {
        $user = Auth::user();

        User::where('id', $user->id)->update([
            'logout_mobile' => Carbon::now('Asia/Jakarta')
        ]);

        Activity::log($user->id, 'Logout', 'Logout', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

        $logout = $user->token()->revoke();

        if($logout){
            $response = [
                'success' => true,
                'message' => 'Successfully logged out',
                'data' => []
            ];
            return response()->json($response);
        } else {
            $response = [
                'success' => false,
                'message' => Helper::errorCode(1323),
                'data' => []
            ];
            return response()->json($response);
        }
    }
}
