@extends('layouts.managerLayout.managerContent')
@section('title', 'Absensi')

@section('content')

<section class="content-header">
	<h1>
		Laporan
		<small>Control panel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Laporan</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
                <div class="col-md-6" style="margin-top: 30px;">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Laporan Bulanan</h3>
						</div>
						<form class="form-horizontal" id="form_laporan_manager" autocomplete="off">
							{{ csrf_field() }}
							<div class="box-body">
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Karyawan</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control select2" name="karyawan" id="karyawan_laporan_manager" required>
											<option value="">Pilih Karyawan</option>
                                            @foreach($karyawan as $key => $value)
											<option value="{{Crypt::encrypt($value->id)}}">{{$value->name}}</option>
                                            @endforeach
										</select>
									</div>
								</div>
                                <div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Bulan</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right perbulan" id="bulan_laporan_manager" name="bulan" autocomplete="off" required>
						                </div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit" class="btn btn-info pull-right" id="btn_submit_manager"><i class="fa fa-print"></i> Cetak</button>
							</div>
							<!-- /.box-footer -->
						</form>
					</div> 
				</div>
			</div>
		</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			
		</div>
	</div>
</section>
@endsection

@section('extra_script')
<script type="text/javascript">

	$("#form_laporan_manager").submit(function(evt) {
		evt.preventDefault();
        let karyawan = $('#karyawan_laporan_manager').val();
        let bulan = $('#bulan_laporan_manager').val();
        let url = `{{url('/manager/laporan/pdf')}}?karyawan=${karyawan}&bulan=${bulan}`;
        window.open(url, '_blank');
	})
</script>
@endsection