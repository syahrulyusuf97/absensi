@extends('layouts.karyawanLayout.karyawanContent')
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
                <div class="col-md-6" style="margin-top: 30px;">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Form Tambah Izin</h3>
						</div>
						<form class="form-horizontal" id="form_izin" autocomplete="off">
							{{ csrf_field() }}
							<div class="box-body">
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Jenis Izin</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control" name="jenis_izin" id="jenis_izin" required>
											<option value="">Pilih Jenis izin</option>
											<option value="SAKIT">SAKIT</option>
											<option value="CUTI">CUTI</option>
										</select>
										<small id="jenisIzinAlert" class="form-text text-danger d-none">Pilih Jenis Izin</small>
									</div>
								</div>
                                <div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Tanggal</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right input-datepicker" id="datepicker" name="tanggal" autocomplete="off" required>
						                </div>
										<small id="tanggalAlert" class="form-text text-danger d-none">Masukkan tanggal</small>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Keterangan</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" required></textarea>
										<small id="eteranganAlert" class="form-text text-danger d-none">Masukkan keterangan</small>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit" class="btn btn-info pull-right" id="btn_submit"><i class="fa fa-save"></i> Simpan</button>
							</div>
							<!-- /.box-footer -->
						</form>
					</div> 
				</div>
			</div>
		</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Izin</h3>
					<div class="pull-right"><button type="button" onclick="refreshTable()" class="btn btn-default"><i class="fa fa-refresh"></i></button></div>
				</div>
				<div class="box-body table-responsive">
					<table id="table_izin" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Jenis Izin</th>
								<th>Keterangan</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th>Tanggal</th>
								<th>Jenis Izin</th>
								<th>Keterangan</th>
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
    let table_izin;

    $(document).on('turbolinks:load', function(){
		table_izin = $('#table_izin').dataTable({
			"processing": true,
			"serverSide": true,
			"destroy": true,
			"ajax": "{{ route('getKaryawanDataIzin') }}",
			"columns":[
				{"data": "tanggal_izin"},
				{"data": "jenis_izin"},
				{"data": "keterangan"},
				{"data": "approve"}
			]
		})
	})

	function refreshTable() {
		table_izin.api().ajax.reload();
	}

	$("#form_izin").submit(function(evt) {
		evt.preventDefault();
		$('#btn_submit').attr('disabled', true);
    	showLoading();
    	axios.post("{{url('/izin/sakit-cuti')}}", $("#form_izin").serialize())
    	  .then(function (response) {
    	  	hideLoading();
    	  	$('#btn_submit').attr('disabled', false);
    	  	if (response.data.success) {
    	  		$("#form_izin")[0].reset();
    	  		table_izin.api().ajax.reload();
    	  		alertSuccess('Berhasil', response.data.message);
    	  	} else {
    	  		alertWarning('Gagal', response.data.message);
    	  	}
    	  })
    	  .catch(function (error) {
    	  	hideLoading();
    	  	$('#btn_submit').attr('disabled', false);
    	    alertError('Error', error);
    	  });
	})
</script>
@endsection