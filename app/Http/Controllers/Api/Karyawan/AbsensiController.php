<?php

namespace App\Http\Controllers\Api\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Absensi;
use App\Izin;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DB;
use Response;
use Auth;
use Helper;

class AbsensiController extends Controller
{
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
                'message' => $activity. " berhasil disimpan",
                'data'    => []
            ];
        }catch(Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => $activity. " gagal disimpan",
                'data'    => []
            ];
        }

        return response()->json($response);
    }

    public function getAbsen()
    {
        if ($request->isMethod('get')) {
            $limit  = $request->limit ?? 5;
            $offset = $request->offset ?? 0;

            $absen_count = Absensi::where('id_karyawan', Auth::user()->id)
                ->selectRaw("count(distinct(id)) as total")
		        ->first();

            $absen = Absensi::where('id_karyawan', Auth::user()->id);

            $total_filtered = $absen->count();

            $absen_results = $absen
                ->limit($limit)
                ->offset($offset)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($items, $key) {
                    $data['id']            = encrypt($items->id);
                    $data['tanggal_absen'] = $items->tanggal_absen;
                    $data['jenis_absen']   = $items->jenis_absen;
                    $data['jam_datang']    = Carbon::parse($items->jam_datang)->format('H:i:s');
                    $data['jam_pulang']    = Carbon::parse($items->jam_pulang)->format('H:i:s');
                    $data['latitude']      = $items->latitude;
                    $data['longitude']     = $items->longitude;
                    $data['status']        = $items->status;
                    $data['nik']           = $items->karyawan->nip;
                    $data['nama_karyawan'] = $items->karyawan->name;
                    $data['id_karyawan']   = encrypt($items->id_karyawan);
                    $data['created_at']    = Carbon::parse($items->created_at)->format("Y-m-d H:i:s");
                    $data['updated_at']    = Carbon::parse($items->updated_at)->format("Y-m-d H:i:s");
                    return $data;
                });

                $results = [
                    'records_total'     => $absen_count->total,
                    'records_filtered'  => $total_filtered,
                    'records_limit'     => $limit,
                    'records_offset'    => $offset,
                    'records'           => $absen_results
                ];

                $response = [
                    'success' => true,
                    'message' => 'data available',
                    'data'    => $results
                ];
        } else {
            $response = [
                'success'   => false,
                'message'   => Helper::errorCode(1106),
                'data'      => []
            ];
        }

        return response()->json($response);
    }
}
