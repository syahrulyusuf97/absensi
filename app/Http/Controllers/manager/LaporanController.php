<?php

namespace App\Http\Controllers\manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\User;
use App\Izin;
use App\Absensi;
use DataTables;
use DB;
use PDF;
use Auth;
use Helper;
use Response;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class LaporanController extends Controller
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

        $karyawan = User::where('level', 2)->where('is_active', 1)->get();
        
        return view('manager.laporan.index')->with(compact('karyawan'));
    }

    public function pdf(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        try {
            $id_karyawan = Crypt::decrypt($request->karyawan);
        } catch (DecryptException $e) {
            $response = [
                'success' => false,
                'message' => "Data tidak valid"
            ];
            
            return redirect()->back()->with('flash_message_error', 'Data tidak valid');
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
        
        $pdf = PDF::loadView('manager.laporan.pdf', $data);
        // return $pdf->download('Cashflow.pdf');
        return $pdf->stream('Laporan.pdf');
    }
}
