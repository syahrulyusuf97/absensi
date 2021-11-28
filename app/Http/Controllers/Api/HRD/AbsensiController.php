<?php

namespace App\Http\Controllers\Api\HRD;

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
    public function getAbsen()
    {
        if ($request->isMethod('get')) {
            $limit = $request->limit ?? 5;
            $offset = $request->offset ?? 0;

            $absen_count = Absensi::selectRaw("count(distinct(id)) as total")
		        ->first();

            $absen = Absensi::with(['karyawan']);

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
