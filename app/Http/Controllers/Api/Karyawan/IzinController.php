<?php

namespace App\Http\Controllers\Api\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivityController as Activity;
use App\User;
use App\Izin;
use App\Absensi;
use DataTables;
use DB;
use Auth;
use Helper;
use Response;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class IzinController extends Controller
{
    public function izin(Request $request)
    {
        $jenis_izin     = $request->jenis_izin ?? null;
        $tanggal_izin   = date('Y-m-d', strtotime($request->tanggal)) ?? null;
        $keterangan     = $request->keterangan ?? null;
        $id_karyawan    = Auth::user()->id;
        $activity       = null;
        $approve        = null;

        $date   = Carbon::parse(date('Y-m-d', strtotime($request->tanggal)));
        $now    = Carbon::now('Asia/Jakarta');
        $diff   = $date->diffInDays($now);

        if ($jenis_izin == "SAKIT") {
            $activity   = 'Izin Sakit';
            $approve    = 1;

            $terakhir_absen_masuk = Absensi::where('id_karyawan', Auth::user()->id)->where('jenis_absen', 'IN')->orderBy('tanggal_absen', 'desc')->first()->tanggal_absen;

            $date_terakhir_absen_masuk  = Carbon::parse($terakhir_absen_masuk);
            $tanggal_pengajuan          = Carbon::parse(date('Y-m-d', strtotime($request->tanggal)));
            $diff                       = $date_terakhir_absen_masuk->diffInDays($tanggal_pengajuan);

            if ($diff > 3) {
                $response = [
                    'success' => false,
                    'message' => "Izin sakit bisa diinputkan maksimal H+3 sejak tanggal ketidakhadiran karyawan",
                    'data'    => []
                ];

                return response()->json($response);
            }
        } else {
            $activity   = 'Izin Cuti';
            $approve    = 0;

            $terakhir_absen_masuk = Absensi::where('id_karyawan', Auth::user()->id)->where('jenis_absen', 'IN')->orderBy('tanggal_absen', 'desc')->first()->tanggal_absen;

            $date_terakhir_absen_masuk  = Carbon::parse($terakhir_absen_masuk);
            $tanggal_pengajuan          = Carbon::parse(date('Y-m-d', strtotime($request->tanggal)));
            $diff                       = $date_terakhir_absen_masuk->diffInDays($tanggal_pengajuan);

            if ($diff > 0) {
                $response = [
                    'success' => false,
                    'message' => "Izin cuti maksimal H-1 dari rencana ketidakhadiran karyawan",
                    'data'    => []
                ];

                return response()->json($response);
            }
        }

        DB::beginTransaction();
        try{
            $izin = new Izin;
            $izin->id_karyawan  = $id_karyawan;
            $izin->jenis_izin   = $jenis_izin;
            $izin->tanggal_izin = $tanggal_izin;
            $izin->keterangan   = $keterangan;
            $izin->approve      = $approve;
            $izin->created_at   = Carbon::now('Asia/Jakarta');
            $izin->updated_at   = Carbon::now('Asia/Jakarta');
            $izin->save();

            Activity::log(Auth::user()->id, 'Create', $activity, date('d-m-Y', strtotime($tanggal_izin)) . ' ' .$activity, null, Carbon::now('Asia/Jakarta'));

            DB::commit();

            $response = [
                'success' => true,
                'message' => $activity. " berhasil disimpan",
                'data'    => []
            ];
        }catch(Exception $e){
            DB::rollback();
            $response = [
                'success'   => false,
                'message'   => $activity. " gagal disimpan",
                'data'      => []
            ];
        }

        return response()->json($response);
    }

    public function getIzin()
    {
        if ($request->isMethod('get')) {
            $limit  = $request->limit ?? 5;
            $offset = $request->offset ?? 0;

            $izin_count = Izin::where('id_karyawan', Auth::user()->id)
                ->selectRaw("count(distinct(id)) as total")
		        ->first();

            $izin = Izin::where('id_karyawan', Auth::user()->id);

            $total_filtered = $izin->count();

            $izin_results = $izin
                ->limit($limit)
                ->offset($offset)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($items, $key) {
                    $data['id']            = encrypt($items->id);
                    $data['tanggal_izin']  = $items->tanggal_izin;
                    $data['jenis_izin']    = $items->jenis_izin;
                    $data['keterangan']    = $items->keterangan;
                    $data['approve']       = $items->approve;
                    $data['nik']           = $items->karyawan->nip;
                    $data['nama_karyawan'] = $items->karyawan->name;
                    $data['id_karyawan']   = encrypt($items->id_karyawan);
                    $data['created_at']    = Carbon::parse($items->created_at)->format("Y-m-d H:i:s");
                    $data['updated_at']    = Carbon::parse($items->updated_at)->format("Y-m-d H:i:s");
                    return $data;
                });

                $results = [
                    'records_total'     => $izin_count->total,
                    'records_filtered'  => $total_filtered,
                    'records_limit'     => $limit,
                    'records_offset'    => $offset,
                    'records'           => $izin_results
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
