@php
    function bulan_periode($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = bulan
        // variabel pecahkan 1 = tahun

        return $bulan[ (int)$pecahkan[0] ] . ' ' . $pecahkan[1];
    }
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            body * {
                font-family: Arial, Helvetica, sans-serif;
            }
            .text-center {
                text-align: center;
            }

            .text-left {
                text-align: left;
            }

            .text-right {
                text-align: right;
            }

            .text-bold {
                font-weight: bold;
            }

            .f16 {
                font-size: 16px;
            }

            .f14 {
                font-size: 14px;
            }

            .box-body{border-top-left-radius:0;border-top-right-radius:0;border-bottom-right-radius:3px;border-bottom-left-radius:3px;padding:10px}

            .table-profil {
                font-family: sans-serif;
                font-size: 12px;
                color: #232323;
                /* border-collapse: collapse; */
                width: 100%;
            }

            .table-profil, tr, td {
                border: none;
                padding: 5px;
            }

            .table-absen {
                font-family: sans-serif;
                color: #232323;
                border-collapse: collapse;
                width: 100%;
                font-size: 10px;
            }

            .table-absen, th, td {
                border: 1px solid #999;
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <h1 class="text-center text-bold f16">Laporan Kehadiran Karyawan</h1>
        <h1 class="text-center text-bold f14">Periode {{ bulan_periode(date('m-Y', strtotime($periode))) }}</h1>

        <div class="box-body">
            <table class="table-profil" style="margin-bottom: 8px;">
                <tr>
                    <td style="width:20%; border: none;">NIP</td>
                    <td style="width:1%; border: none;">:</td>
                    <td style="border: none;">{{$karyawan->nip}}</td>
                </tr>
                <tr>
                    <td style="border: none;">NAMA</td>
                    <td style="border: none;">:</td>
                    <td style="border: none;">{{$karyawan->name}}</td>
                </tr>
                <tr>
                    <td style="border: none;">Jenis Kelamin</td>
                    <td style="border: none;">:</td>
                    <td style="border: none;">{{$karyawan->sex}}</td>
                </tr>
                <tr>
                    <td style="border: none;">EMAIL</td>
                    <td style="border: none;">:</td>
                    <td style="border: none;">{{$karyawan->email}}</td>
                </tr>
            </table>
            <h4 class="text-center">ABSENSI</h4>
            <table class="table-absen" style="margin-bottom: 8px;">
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Absen</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                </tr>
                @foreach($absen as $key => $value)
                <tr>
                    <td style="text-align: center;">{{$value->tanggal_absen}}</td>
                    <td style="text-align: center;">{{$value->jenis_absen}}</td>
                    <td style="text-align: center;">@if($value->jam_datang != ""){{date('H:i:s', strtotime($value->jam_datang))}}@endif</td>
                    <td style="text-align: center;">@if($value->jam_pulang != ""){{date('H:i:s', strtotime($value->jam_pulang))}}@endif</td>
                    <td>Lat:{{$value->latitude}}, Lng:{{$value->latitude}}</td>
                    <td style="text-align: center;">{{$value->status}}</td>
                </tr>
                @endforeach
            </table>

            <h4 class="text-center">IZIN SAKIT/CUTI</h4>
            <table class="table-absen" style="margin-bottom: 8px;">
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Izin</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                </tr>
                @foreach($izin as $key => $value)
                <tr>
                    <td style="text-align: center;">{{$value->tanggal_izin}}</td>
                    <td style="text-align: center;">{{$value->jenis_izin}}</td>
                    <td>{{$value->keterangan}}</td>
                    <td style="text-align: center;">{{Helper::statusApprovalIzin($value->approve)}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </body>
</html>