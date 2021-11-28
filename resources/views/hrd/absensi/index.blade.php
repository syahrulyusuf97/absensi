@extends('layouts.hrdLayout.hrdContent')
@section('title', 'Absensi')

@section('content')

<style>
    .btn-oval {
        display:block;
        height: 300px;
        width: 300px;
        border-radius: 50%;
        border: 1px solid blue;
        background-color: blue;
        color: #fff;
        font-size: 25px;
        margin: auto;
    }
</style>

<section class="content-header">
	<h1>
		Absensi
		<small>Control panel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Absensi</li>
	</ol>
</section>

<section class="content">
	<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Absen</h3>
					<div class="pull-right"><button type="button" onclick="refreshTable()" class="btn btn-default"><i class="fa fa-refresh"></i></button></div>
				</div>
				<div class="box-body table-responsive">
					<table id="table_absen_hrd" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Karyawan</th>
								<th>Jenis Absen</th>
								<th>Jam Masuk</th>
								<th>Jam Pulang</th>
								<th>Lokasi</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th>Tanggal</th>
								<th>Karyawan</th>
								<th>Jenis Absen</th>
								<th>Jam Masuk</th>
								<th>Jam Pulang</th>
								<th>Lokasi</th>
								<th>Status</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('extra_script')
<script type="text/javascript">
    let table_absen_hrd;

    $(document).on('turbolinks:load', function(){
        table_absen_hrd = $('#table_absen_hrd').dataTable({
			"processing": true,
			"serverSide": true,
			"destroy": true,
			"ajax": "{{ route('hrdGetKaryawanDataAbsen') }}",
			"columns":[
				{"data": "tanggal_absen"},
				{"data": "karyawan"},
				{"data": "jenis_absen"},
				{"data": "jam_datang"},
				{"data": "jam_pulang"},
				{"data": "location"},
				{"data": "status"}
			]
		})
	})

	function refreshTable() {
		table_absen_hrd.api().ajax.reload();
	}
</script>
@endsection