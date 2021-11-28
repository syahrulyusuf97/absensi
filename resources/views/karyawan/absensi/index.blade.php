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

	.pointer {
		cursor: pointer;
	}

	.notallowed {
		cursor: not-allowed;
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
                <div class="col-md-12" style="margin-top: 30px;">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Klik tombol dibawah ini untuk memulai absen</h3>
						</div>
						<button type="button" class="btn-oval @if($isDisabled) notallowed @else pointer @endif" id="btn_absen" onclick="getLocation()" @if($isDisabled) disabled @endif>{{$text_button_absen}}</button>
					</div>
				</div>
			</div>
		</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Absen</h3>
					<div class="pull-right"><button type="button" onclick="refreshTable()" class="btn btn-default"><i class="fa fa-refresh"></i></button></div>
				</div>
				<div class="box-body table-responsive">
					<table id="table_absen" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Tanggal</th>
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
    let table_absen;
    let latitude = null;
    let longitude = null;

    $(document).on('turbolinks:load', function(){
		table_absen = $('#table_absen').dataTable({
			"processing": true,
			"serverSide": true,
			"destroy": true,
			"ajax": "{{ route('getKaryawanDataAbsen') }}",
			"columns":[
				{"data": "tanggal_absen"},
				{"data": "jenis_absen"},
				{"data": "jam_datang"},
				{"data": "jam_pulang"},
				{"data": "location"},
				{"data": "status"}
			]
		})
	})

	function refreshTable() {
		table_absen.api().ajax.reload();
	}

	function getLocation() {
		
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition, showError);
		} else { 
			alert('Location Not allowed');
		}
	}

	function showError(error) {
		// alert(`Error : ${error.message}`)
		alert(`Koneksi internet terputus!`)
	}

	function showPosition(position) {
		let data = {
			_token : `{{csrf_token()}}`,
			latitude : position.coords.latitude,
			longitude : position.coords.longitude
		};

    	showLoading();

    	axios.post("{{url('/absen')}}", data)
    	  .then(function (response) {
    	  	hideLoading();
    	  	if (response.data.success) {
				$('#btn_absen').text('ANDA SUDAH ABSEN');
				$('#btn_absen').attr('disabled', true);
				table_absen.api().ajax.reload();
    	  		alertSuccess('Berhasil', response.data.message);
    	  	} else {
				$('#btn_absen').attr('disabled', false);
    	  		alertWarning('Gagal', response.data.message);
    	  	}
    	  })
    	  .catch(function (error) {
    	  	hideLoading();
    	  	$('#btn_absen').attr('disabled', false);
    	    alertError('Error', error);
    	  });
	}
</script>
@endsection