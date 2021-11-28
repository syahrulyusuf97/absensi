<?php

namespace App\Http\Controllers\Api\HRD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\User;
use App\Izin;
use App\Absensi;
use DB;
use Auth;
use Helper;
use Response;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class LaporanController extends Controller
{
    public function getKaryawan()
    {
        $karyawan = User::where('level', 2)->where('is_active', 1)->get()->map(function ($items, $key) {
            $data['id']             = encrypt($items->id);
            $data['nip']            = $items->nip;
            $data['name']           = $items->name;
            $data['sex']            = $items->sex;
            $data['tempat_lahir']   = $items->tempat_lahir;
            $data['tgl_lahir']      = $items->tgl_lahir;
            $data['address']        = $items->address;
            $data['created_at']     = Carbon::parse($items->created_at)->format("Y-m-d H:i:s");
            $data['updated_at']     = Carbon::parse($items->updated_at)->format("Y-m-d H:i:s");
            return $data;
        });
        
        $response = [
            'success' => true,
            'message' => "data available",
            'data'    => $karyawan
        ];

        return response()->json($response);
    }

    public function laporan(Request $request)
    {
        try {
            $id_karyawan = Crypt::decrypt($request->karyawan);
        } catch (DecryptException $e) {
            $response = [
                'success'   => false,
                'message'   => "Data tidak valid",
                'data'      => []
            ];

            return response()->json($response);
        }

        $month = explode(" ", $request->bulan);

        $data['karyawan'] = User::where('id', $id_karyawan)->first();

        $data['absen'] =  Absensi::with(['karyawan'])
            ->where('id_karyawan', $id_karyawan)
            ->whereMonth('tanggal_absen', date('m', strtotime($month[0])))
            ->whereYear('tanggal_absen', $month[1])
            ->get();

        $data['izin'] =  Izin::with(['karyawan'])
            ->where('id_karyawan', $id_karyawan)
            ->whereMonth('tanggal_izin', date('m', strtotime($month[0])))
            ->whereYear('tanggal_izin', $month[1])
            ->get();

        $data['periode'] = $request->bulan;

        $response = [
            'success' => true,
            'message' => "data available",
            'data'    => $data
        ];

        return response()->json($response);
    }
}
