<?php

namespace App\Http\Controllers\karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivityController as Activity;
use App\User;
use App\Absensi;
use DataTables;
use DB;
use Auth;
use Helper;
use Response;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class AbsensiController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        $text_button_absen = 'ABSEN SEKARANG';

        // Untuk disabled/enable button absen

        $absen_masuk_hari_ini = Absensi::where('id_karyawan', Auth::user()->id)->where('jenis_absen', 'IN')->where('tanggal_absen', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->exists();

        $absen_pulang_hari_ini = Absensi::where('id_karyawan', Auth::user()->id)->where('jenis_absen', 'OUT')->where('tanggal_absen', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->exists();
        
        $isDisabled = false;

        $currentTime = time();

        if (((int) date('H', $currentTime)) < 17) {
            if ($absen_masuk_hari_ini) {
                $isDisabled = true;
                $text_button_absen = 'ANDA SUDAH ABSEN';
            }
        } elseif (((int) date('H', $currentTime)) > 17) {
            $isDisabled = false;
            if ($absen_pulang_hari_ini) {
                $isDisabled = true;
                $text_button_absen = 'ANDA SUDAH ABSEN';
            }
        }

        return view('karyawan.absensi.index')->with(compact('isDisabled', 'text_button_absen'));
    }

    public function absen(Request $request)
    {
        $currentTime    = time();
        $latitude       = $request->latitude ?? null;
        $longitude      = $request->longitude ?? null;
        $id_karyawan    = Auth::user()->id;
        $tanggal_absen  = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $jam_datang     = null;
        $jam_pulang     = null;
        $activity       = null;

        if (((int) date('H', $currentTime)) < 17) {
            $jenis_absen = 'IN';

            if (((int) date('H', $currentTime)) < 9) {
                $status = 'VALID';
            } elseif (((int) date('H', $currentTime)) >= 9) {
                $status = 'TIDAK VALID';
            }

            $jam_datang = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $activity   = 'Absen Masuk';
        } elseif (((int) date('H', $currentTime)) > 17) {
            $jenis_absen    = 'OUT';
            $status         = 'VALID';
            $jam_pulang     = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $activity       = 'Absen Pulang';
        }

        DB::beginTransaction();
        try{
            $absen = new Absensi;
            $absen->id_karyawan     = $id_karyawan;
            $absen->tanggal_absen   = $tanggal_absen;
            $absen->jenis_absen     = $jenis_absen;
            $absen->jam_datang      = $jam_datang;
            $absen->jam_pulang      = $jam_pulang;
            $absen->latitude        = $latitude;
            $absen->longitude       = $longitude;
            $absen->status          = $status;
            $absen->created_at      = Carbon::now('Asia/Jakarta');
            $absen->updated_at      = Carbon::now('Asia/Jakarta');
            $absen->save();

            Activity::log(Auth::user()->id, 'Create', $activity, date('d-m-Y', strtotime($tanggal_absen)) . ' ' .$activity, null, Carbon::now('Asia/Jakarta'));

            DB::commit();

            $response = [
                'success' => true,
                'message' => $activity. " berhasil disimpan"
            ];
        }catch(Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => $activity. " gagal disimpan"
            ];
        }

        return response()->json($response);
    }

    public function getAbsen()
    {
        $data = Absensi::where('id_karyawan', Auth::user()->id)
        ->orderBy('created_at', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal_absen', function ($data) {

                return date('d-m-Y', strtotime($data->tanggal_absen));

            })

            ->addColumn('jenis_absen', function ($data) {

                return '<p class="text-center">'.$data->jenis_absen.'</p>';

            })

            ->addColumn('jam_datang', function ($data) {

                return ($data->jam_datang != "") ? '<p class="text-center">'.Carbon::parse($data->jam_datang)->format('H:i:s').'</p>' : null;

            })

            ->addColumn('jam_pulang', function ($data) {

                return ($data->jam_pulang != "") ? '<p class="text-center">'.Carbon::parse($data->jam_pulang)->format('H:i:s').'</p>' : null;

            })

            ->addColumn('location', function ($data) {

                return '<a class="label label-success" href="http://www.google.com/maps/place/'.$data->latitude.','.$data->longitude.'" target="_blank">View on GMAPS</a>';

            })

            ->addColumn('status', function ($data) {

                return ($data->status == "VALID") ? '<a class="label label-success" href="javascript:void(0)">VALID</a>' : '<a class="label label-danger" href="javascript:void(0)">TIDAK VALID</a>';

            })

            ->rawColumns(['tanggal_absen', 'jenis_absen', 'jam_datang', 'jam_pulang', 'location', 'status'])

            ->make(true);
    }
}
