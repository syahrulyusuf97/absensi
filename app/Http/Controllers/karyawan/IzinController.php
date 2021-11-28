<?php

namespace App\Http\Controllers\karyawan;

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
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        return view('karyawan.izin.index');
    }

    public function izin(Request $request)
    {
        $jenis_izin     = $request->jenis_izin ?? null;
        $tanggal_izin   = date('Y-m-d', strtotime($request->tanggal)) ?? null;
        $keterangan     = $request->keterangan ?? null;
        $id_karyawan    = Auth::user()->id;
        $activity       = null;
        $approve        = null;
        $date           = Carbon::parse(date('Y-m-d', strtotime($request->tanggal)));
        $now            = Carbon::now('Asia/Jakarta');
        $diff           = $date->diffInDays($now);

        if ($jenis_izin == "SAKIT") {
            $activity = 'Izin Sakit';
            $approve  = 1;

            $terakhir_absen_masuk = Absensi::where('id_karyawan', Auth::user()->id)->where('jenis_absen', 'IN')->orderBy('tanggal_absen', 'desc')->first()->tanggal_absen;

            $date_terakhir_absen_masuk  = Carbon::parse($terakhir_absen_masuk);
            $tanggal_pengajuan          = Carbon::parse(date('Y-m-d', strtotime($request->tanggal)));
            $diff                       = $date_terakhir_absen_masuk->diffInDays($tanggal_pengajuan);
            if ($diff > 3) {
                $response = [
                    'success' => false,
                    'message' => "Izin sakit bisa diinputkan maksimal H+3 sejak tanggal ketidakhadiran karyawan"
                ];

                return response()->json($response);
            }
        } else {
            $activity = 'Izin Cuti';
            $approve  = 0;

            $terakhir_absen_masuk = Absensi::where('id_karyawan', Auth::user()->id)->where('jenis_absen', 'IN')->orderBy('tanggal_absen', 'desc')->first()->tanggal_absen;

            $date_terakhir_absen_masuk  = Carbon::parse($terakhir_absen_masuk);
            $tanggal_pengajuan          = Carbon::parse(date('Y-m-d', strtotime($request->tanggal)));
            $diff                       = $date_terakhir_absen_masuk->diffInDays($tanggal_pengajuan);

            if ($diff > 0) {
                $response = [
                    'success' => false,
                    'message' => "Izin cuti maksimal H-1 dari rencana ketidakhadiran karyawan"
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

    public function getIzin()
    {
        $data = Izin::where('id_karyawan', Auth::user()->id)
        ->orderBy('tanggal_izin', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal_izin', function ($data) {

                return date('d-m-Y', strtotime($data->tanggal_izin));

            })

            ->addColumn('jenis_izin', function ($data) {

                return '<p class="text-center">'.$data->jenis_izin.'</p>';

            })

            ->addColumn('keterangan', function ($data) {

                return '<p class="text-left">'.$data->keterangan.'</p>';

            })

            ->addColumn('approve', function ($data) {
                if ($data->approve == "0") {
                    return '<a class="label label-warning" href="javascript:void(0)">PENDING</a>';
                } elseif ($data->approve == "1") {
                    return '<a class="label label-success" href="javascript:void(0)">APPROVED</a>';
                } elseif ($data->approve == "2") {
                    return '<a class="label label-danger" href="javascript:void(0)">REJECTED</a>';
                }

            })

            ->rawColumns(['tanggal_izin', 'jenis_izin', 'keterangan', 'approve'])

            ->make(true);
    }
}
