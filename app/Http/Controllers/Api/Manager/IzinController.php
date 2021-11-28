<?php

namespace App\Http\Controllers\Api\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivityController as Activity;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
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
    public function getIzin()
    {
        if ($request->isMethod('get')) {
            $limit  = $request->limit ?? 5;
            $offset = $request->offset ?? 0;

            $izin_count = Izin::selectRaw("count(distinct(id)) as total")
		        ->first();

            $izin = Izin::with(['karyawan']);

            $total_filtered = $izin->count();

            $izin_results = $izin
                ->limit($limit)
                ->offset($offset)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($items, $key) {
                    $data['id']             = encrypt($items->id);
                    $data['tanggal_izin']   = $items->tanggal_izin;
                    $data['jenis_izin']     = $items->jenis_izin;
                    $data['keterangan']     = $items->keterangan;
                    $data['approve']        = $items->approve;
                    $data['nik']            = $items->karyawan->nip;
                    $data['nama_karyawan']  = $items->karyawan->name;
                    $data['id_karyawan']    = encrypt($items->id_karyawan);
                    $data['created_at']     = Carbon::parse($items->created_at)->format("Y-m-d H:i:s");
                    $data['updated_at']     = Carbon::parse($items->updated_at)->format("Y-m-d H:i:s");
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
                    'success'   => true,
                    'message'   => 'data available',
                    'data'      => $results
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

    public function approved($id = null)
    {
        DB::beginTransaction();
        try{
            Izin::where(['id'=>$id])->update([
                'approve'       => 1,
                'updated_at'    => Carbon::now('Asia/Jakarta')
            ]);

            Activity::log(Auth::user()->id, 'Update', 'Approve Izin Cuti',  Carbon::now('Asia/Jakarta')->format('d-m-Y') . ' Approve Izin Cuti', null, Carbon::now('Asia/Jakarta'));

            DB::commit();

            $response = [
                'success'   => true,
                'message'   => "Approve Izin Cuti berhasil disimpan",
                'data'      => []
            ];
        }catch(Exception $e){
            DB::rollback();
            $response = [
                'success'   => false,
                'message'   => "Approve Izin Cuti gagal disimpan",
                'data'      => []
            ];
        }

        return response()->json($response);
    }

    public function rejected($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $response = [
                'success'   => false,
                'message'   => "Data tidak valid",
                'data'      => []
            ];
            return response()->json($response);
        }

        DB::beginTransaction();
        try{
            Izin::where(['id'=>$id])->update([
                'approve'       => 2,
                'updated_at'    => Carbon::now('Asia/Jakarta')
            ]);

            Activity::log(Auth::user()->id, 'Update', 'Tolak Izin Cuti', Carbon::now('Asia/Jakarta')->format('d-m-Y') . ' Tolak Izin Cuti', null, Carbon::now('Asia/Jakarta'));

            DB::commit();

            $response = [
                'success'   => true,
                'message'   => "Tolak Izin Cuti berhasil disimpan",
                'data'      => []
            ];
        }catch(Exception $e){
            DB::rollback();
            $response = [
                'success'   => false,
                'message'   => "Tolak Izin Cuti gagal disimpan",
                'data'      => []
            ];
        }

        return response()->json($response);
    }
}
