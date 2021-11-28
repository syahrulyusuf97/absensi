<?php

namespace App\Http\Controllers\hrd;

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
            if (Auth::user()->level != 3) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        return view('hrd.absensi.index');
    }

    public function getAbsen()
    {
        $data = Absensi::with(['karyawan'])->orderBy('created_at', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal_absen', function ($data) {

                return '<p class="text-center">'.date('d-m-Y', strtotime($data->tanggal_absen)).'</p>';

            })

            ->addColumn('karyawan', function ($data) {

                return ($data->karyawan->nip == "") ? '<p class="text-left">'.$data->karyawan->name.'</p>' : '<p class="text-left">'.'('.$data->karyawan->nip.') '.$data->karyawan->name.'</p>';

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

            ->rawColumns(['tanggal_absen', 'karyawan', 'jenis_absen', 'jam_datang', 'jam_pulang', 'location', 'status'])

            ->make(true);
    }
}
