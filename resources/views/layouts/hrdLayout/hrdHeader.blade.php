<header class="main-header">
	<!-- Logo -->
	<a href="{{url('/')}}" class="logo" data-turbolinks="true">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>Absensi</b>Karyawan</span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b>Absensi</b>Karyawan</span>
	</a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top">
		<!-- Sidebar toggle button-->
		<a href="#" data-turbolinks="false" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" data-turbolinks="false" class="dropdown-toggle" data-toggle="dropdown">
						@if(auth()->user()->img == "")
							<img src="{{ asset('images/default.jpg') }}" class="user-image" alt="User Image">
						@else
							<img src="{{ asset('images/'.auth()->user()->img) }}" class="user-image" alt="User Image">
						@endif
						<span class="hidden-xs">
							{{ auth()->user()->name }}
						</span>
					</a>
					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header">
							@if(auth()->user()->img == "")
							<img src="{{ asset('images/default.jpg') }}" class="img-circle" alt="User Image">
							@else
							<img src="{{ asset('images/'. auth()->user()->img) }}" class="img-circle" alt="User Image">
							@endif

							<p>
								{{ auth()->user()->name }}
								<small>Human Resources Development</small>
							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="{{ url('/hrd/profil') }}" class="btn btn-default btn-flat" data-turbolinks="true">Profil</a>
							</div>
							<div class="pull-right">
								<a href="{{ url('/logout') }}" class="btn btn-default btn-flat" data-turbolinks="false">Keluar</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>