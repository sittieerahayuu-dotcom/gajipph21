<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <br>
    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center mt-4 mb-3" href="">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('images/lisawb.jpeg') }}" alt="Logo" width="50" height="50">
        </div>
        <div class="sidebar-brand-text text-center mt-2">
            <div><strong>APLIKASI PERHITUNGAN GAJI</strong></div>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard (All Roles) -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    @php
    $role = Auth::user()->role;
    @endphp

    @if($role == 1) {{-- Staff Penggajian --}}
    <!-- Heading -->
    <div class="sidebar-heading">
        Data-Data
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Data Pengguna</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('jabatans.index') }}">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Data Jabatan</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('karyawan.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Karyawan</span>
        </a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        Transaksi
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('kehadiran.index') }}">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Manajemen Absensi</span>
        </a>
    </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('kehadiran.rekap') }}">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Rekap Kehadiran</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('potongangaji.index') }}">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Setting Potongan Gaji</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('perhitungan-pph.index') }}">
            <i class="fas fa-fw fa-calculator"></i>
            <span>Perhitungan PPh 21</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('perhitungan-gaji.index') }}">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Perhitungan Gaji</span>
        </a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        Laporan
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-3 collapse-inner rounded">
                <h6 class="collapse-header">Menu Laporan : </h6>
                <a class="collapse-item" href="{{ route('kehadiran.laporan') }}">Laporan Rekap Kehadiran</a>
                <a class="collapse-item" href="{{ route('perhitungan-pph.laporan') }}">Laporan PPh 21</a>
                <a class="collapse-item" href="{{ route('perhitungan-gaji.laporan') }}">Laporan Perhitungan Gaji</a>
                <!-- <a class="collapse-item" href="{{ route('slip-gaji.form') }}">Slip Gaji Karyawan</a> -->
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('slip-gaji.form') }}">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Slip Gaji</span>
        </a>
    </li>
    @elseif($role == 2) {{-- Direktur --}}
    <!-- <div class="sidebar-heading">
        Menu
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('kehadiran.index') }}">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Rekap Kehadiran</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('perhitungan-pph.index') }}">
            <i class="fas fa-fw fa-calculator"></i>
            <span>Perhitungan PPh 21</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('perhitungan-gaji.index') }}">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Perhitungan Gaji</span>
        </a>
    </li> -->

    <!-- Heading -->
    <div class="sidebar-heading">
        Laporan
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu Laporan :</h6>
                <a class="collapse-item" href="{{ route('kehadiran.laporan') }}">Laporan Rekap Kehadiran</a>
                <a class="collapse-item" href="{{ route('perhitungan-pph.laporan') }}">Laporan PPh 21</a>
                <a class="collapse-item" href="{{ route('perhitungan-gaji.laporan') }}">Laporan Perhitungan Gaji</a>
                <!-- <a class="collapse-item" href="{{ route('slip-gaji.form') }}">Slip Gaji Karyawan</a> -->
            </div>
        </div>
    </li>
     <li class="nav-item">
        <a class="nav-link" href="{{ route('slip-gaji.form') }}">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Slip Gaji</span>
        </a>
    </li>
    @elseif($role == 3) {{-- Karyawan --}}
    <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('kehadiran.index') }}">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Absensi Kehadiran</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('slip-gaji.form') }}">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Slip Gaji</span>
        </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>