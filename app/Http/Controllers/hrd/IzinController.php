<?php

namespace App\Http\Controllers\hrd;

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
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        return view('hrd.izin.index');
    }

    public function getIzin()
    {
        $data = Izin::orderBy('tanggal_izin', 'desc');

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
