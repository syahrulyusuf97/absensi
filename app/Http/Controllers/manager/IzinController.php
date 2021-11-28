<?php

namespace App\Http\Controllers\manager;

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
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        return view('manager.izin.index');
    }

    public function getIzin()
    {
        $data = Izin::with(['karyawan'])->orderBy('tanggal_izin', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal_izin', function ($data) {

                return date('d-m-Y', strtotime($data->tanggal_izin));

            })

            ->addColumn('karyawan', function ($data) {

                return '<p class="text-left">'.$data->karyawan->nip.' '.$data->karyawan->name.'</p>';

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

            ->addColumn('action', function ($data) {
                if ($data->jenis_izin == "CUTI") {
                    if ($data->approve == "0") {
                        return '<p class="text-center"><a href="#" data-turbolinks="false" onclick="return alertConfirmUpdate(\''. 'Konfirmasi'.'\', \''. 'Apakah anda yakin akan menyetujui data ini?'.'\', \''.url('/manager/izin/approved/'.Crypt::encrypt($data->id)).'\')" class="text-success" style="padding: 4px; font-size: 14px;"><i class="fa fa-check"></i> Setujui</a>&nbsp; || &nbsp;<a href="#" data-turbolinks="false" onclick="return alertConfirmUpdate(\''. 'Konfirmasi'.'\', \''. 'Apakah anda yakin akan menolak data ini?'.'\', \''.url('/manager/izin/rejected/'.Crypt::encrypt($data->id)).'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-times"></i> Tolak</a></p>';
                    } elseif ($data->approve == "1") {
                        return '<p class="text-center"><a href="#" data-turbolinks="false" onclick="return alertConfirmUpdate(\''. 'Konfirmasi'.'\', \''. 'Apakah anda yakin akan menolak data ini?'.'\', \''.url('/manager/izin/rejected/'.Crypt::encrypt($data->id)).'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-times"></i> Tolak</a></p>';
                    } elseif ($data->approve == "2") {
                        return '<p class="text-center"><a href="#" data-turbolinks="false" onclick="return alertConfirmUpdate(\''. 'Konfirmasi'.'\', \''. 'Apakah anda yakin akan menyetujui data ini?'.'\', \''.url('/manager/izin/approved/'.Crypt::encrypt($data->id)).'\')" class="text-success" style="padding: 4px; font-size: 14px;"><i class="fa fa-check"></i> Setujui</a></p>';
                    }
                }

            })

            ->rawColumns(['tanggal_izin', 'karyawan', 'jenis_izin', 'keterangan', 'approve', 'action'])

            ->make(true);
    }

    public function approved($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $response = [
                'success' => false,
                'message' => "Data tidak valid"
            ];
            return response()->json($response);
        }

        DB::beginTransaction();
        try{
            Izin::where(['id'=>$id])->update([
                'approve'    => 1,
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);

            Activity::log(Auth::user()->id, 'Update', 'Approve Izin Cuti',  Carbon::now('Asia/Jakarta')->format('d-m-Y') . ' Approve Izin Cuti', null, Carbon::now('Asia/Jakarta'));

            DB::commit();

            $response = [
                'success' => true,
                'message' => "Approve Izin Cuti berhasil disimpan"
            ];
        }catch(Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => "Approve Izin Cuti gagal disimpan"
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
                'success' => false,
                'message' => "Data tidak valid"
            ];
            return response()->json($response);
        }

        DB::beginTransaction();
        try{
            Izin::where(['id'=>$id])->update([
                'approve'    => 2,
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);

            Activity::log(Auth::user()->id, 'Update', 'Tolak Izin Cuti', Carbon::now('Asia/Jakarta')->format('d-m-Y') . ' Tolak Izin Cuti', null, Carbon::now('Asia/Jakarta'));

            DB::commit();

            $response = [
                'success' => true,
                'message' => "Tolak Izin Cuti berhasil disimpan"
            ];
        }catch(Exception $e){
            DB::rollback();
            $response = [
                'success' => false,
                'message' => "Tolak Izin Cuti gagal disimpan"
            ];
        }

        return response()->json($response);
    }
}
