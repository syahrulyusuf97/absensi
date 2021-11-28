@extends('layouts.karyawanLayout.karyawanContent')
@section('title', 'Dashboard')

@section('content')

<section class="content-header">
	<h1>
		Dashboard
		<small>Control panel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="small-box bg-blue">
						<div class="inner">
							<h3>{{$total_absen_masuk}}</h3>

							<p>Absen Masuk</p>
						</div>
						<div class="icon">
							<i class="ion ion-home"></i>
						</div>
					</div>
				</div>

				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="small-box bg-green">
						<div class="inner">
							<h3>{{$total_absen_masuk_tepat}}</h3>

							<p>Absen Masuk (Tepat)</p>
						</div>
						<div class="icon">
							<i class="ion ion-arrow-down-a"></i>
						</div>
					</div>
				</div>

				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="small-box bg-red">
						<div class="inner">
							<h3>{{$total_absen_masuk_tidak_tepat}}</h3>

							<p>Absen Masuk (Tidak Tepat)</p>
						</div>
						<div class="icon">
							<i class="ion ion-arrow-up-a"></i>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="small-box bg-red">
						<div class="inner">
							<h3>{{$total_absen_pulang}}</h3>

							<p>Absen Pulang</p>
						</div>
						<div class="icon">
							<i class="ion ion-home"></i>
						</div>
					</div>
				</div>

				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="small-box bg-green">
						<div class="inner">
							<h3>{{$total_absen_pulang_tepat}}</h3>

							<p>Absen Pulang (Tepat)</p>
						</div>
						<div class="icon">
							<i class="ion ion-arrow-down-a"></i>
						</div>
					</div>
				</div>

				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="small-box bg-red">
						<div class="inner">
							<h3>{{$total_absen_pulang_tidak_tepat}}</h3>

							<p>Absen Pulang (Tidak Tepat)</p>
						</div>
						<div class="icon">
							<i class="ion ion-arrow-up-a"></i>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="small-box bg-blue">
						<div class="inner">
							<h3>{{$izin_sakit}}</h3>

							<p>Izin Sakit</p>
						</div>
						<div class="icon">
							<i class="ion ion-home"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3>{{$izin_cuti_pending}}</h3>

							<p>Izin Cuti Pending</p>
						</div>
						<div class="icon">
							<i class="ion ion-home"></i>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="small-box bg-green">
						<div class="inner">
							<h3>{{$izin_cuti_approved}}</h3>

							<p>Izin Cuti Disetujui</p>
						</div>
						<div class="icon">
							<i class="ion ion-home"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="small-box bg-green">
						<div class="inner">
							<h3>{{$izin_cuti_rejected}}</h3>

							<p>Izin Cuti Ditolak</p>
						</div>
						<div class="icon">
							<i class="ion ion-home"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('extra_script')
<script type="text/javascript">
	// 
</script>
@endsection