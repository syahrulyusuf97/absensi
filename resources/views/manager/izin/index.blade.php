@extends('layouts.managerLayout.managerContent')
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
		Izin
		<small>Control panel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Izin</li>
	</ol>
</section>

<section class="content">
	<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Izin</h3>
					<div class="pull-right"><button type="button" onclick="refreshTable()" class="btn btn-default"><i class="fa fa-refresh"></i></button></div>
				</div>
				<div class="box-body table-responsive">
					<table id="table_izin_manager" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Kayawan</th>
								<th>Jenis Izin</th>
								<th>Keterangan</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th>Tanggal</th>
								<th>Kayawan</th>
								<th>Jenis Izin</th>
								<th>Keterangan</th>
								<th>Status</th>
								<th>Aksi</th>
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
    let table_izin_manager;

    $(document).on('turbolinks:load', function(){
        table_izin_manager = $('#table_izin_manager').dataTable({
			"processing": true,
			"serverSide": true,
			"destroy": true,
			"ajax": "{{ route('getManagerDataIzin') }}",
			"columns":[
				{"data": "tanggal_izin"},
				{"data": "karyawan"},
				{"data": "jenis_izin"},
				{"data": "keterangan"},
				{"data": "approve"},
				{"data": "action"},
			]
		})
	})

	function refreshTable() {
		table_izin_manager.api().ajax.reload();
	}
</script>
@endsection