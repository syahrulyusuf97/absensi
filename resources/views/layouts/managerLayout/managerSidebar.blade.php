<!-- <div id="scroll"> -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        @if(auth()->user()->img == "")
          <img src="{{ asset('images/default.jpg') }}" class="img-circle" alt="User Image">
        @else
          <img src="{{ asset('images/'. auth()->user()->img) }}" class="img-circle" alt="User Image">
        @endif
      </div>
      <div class="pull-left info">
        <p>
          {{ auth()->user()->name }}
        </p>
        <a href="#"><i class="fa fa-circle"></i> {{ Helper::userOnlineStatus(Crypt::encrypt(auth()->user()->id)) }}</a>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">NAVIGASI UTAMA</li>
      <li>
        <a href="{{ url('/manager/dashboard') }}" data-turbolinks="true">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ url('/manager/absensi') }}" data-turbolinks="true">
          <i class="fa fa-list-alt"></i> <span>Absensi</span>
        </a>
      </li>
      <li>
        <a href="{{ url('/manager/izin') }}" data-turbolinks="true">
          <i class="fa fa-list"></i> <span>Izin Sakit/Cuti</span>
        </a>
      </li>
      <li>
        <a href="{{ url('/manager/laporan') }}" data-turbolinks="true">
          <i class="fa fa-book"></i> <span>Laporan</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<!-- </div> -->