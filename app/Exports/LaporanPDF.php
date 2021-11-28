<?php

namespace App\Exports;
use App\Absensi;
use App\Izin;
use DB;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class LaporanPDF implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct(string $id_karyawan, string $bulan)
    {
        $this->id_karyawan = $id_karyawan;
        $this->bulan = $bulan;
    }

    public function view(): View
    {
        $periode = $this->bulan;
        
        if (($this->id_karyawan != "" || $this->id_karyawan != null) && ($this->bulan != "" || $this->bulan != null)) {
            $month = explode(" ", $this->bulan);

            $absen =  Absensi::query()
                ->with(['karyawan'])
                ->where('id_karyawan', $this->id_karyawan)
                ->whereMonth('tanggal_absen', date('m', strtotime($month[0])))
                ->whereYear('tanggal_absen', $month[1])
                ->get();

            $izin =  Absensi::query()
                ->with(['karyawan'])
                ->where('id_karyawan', $this->id_karyawan)
                ->whereMonth('tanggal_izin', date('m', strtotime($month[0])))
                ->whereYear('tanggal_izin', $month[1])
                ->get();
            
        }

        if (Auth::user()->level == 1) {
            return view('manager.laporan.pdf', [
                'absen' => $absen,
                'izin' => $izin,
                'periode' => $periode
            ]);
        }
    }
}