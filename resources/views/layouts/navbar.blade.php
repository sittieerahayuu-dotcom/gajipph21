<!-- Topbar Navbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <form class="d-none d-sm-inline-block form-inline">
        <div class="logo-text-container">
            <!-- <img src="{{ asset('images/lisawb.jpeg') }}" alt="Logo BSI" style="width: 55px; height: 45px;" class="mr-2"> -->
            <div>
                <div class="h6 mb-0 d-lg-inline text-gray-600 small">SELAMAT DATANG DI APLIKASI PENGGAJIAN DAN PERHITUNGAN PAJAK PENGHASILAN 21</div>
                <div class="h6 mb-0 text-gray-600 small">CV. LISA WANGI BANDUNG</div>
            </div>
        </div>
    </form>

    <!-- Tambahkan gaya CSS -->
    <style>
        .form-inline {
            padding-left: 10px;
            /* Memberikan sedikit jarak dari kiri */
        }

        .logo-text-container {
            display: flex;
            align-items: center;
            /* Membuat logo dan teks sejajar secara vertikal */
        }

        .logo-text-container img {
            margin-right: 10px;
            /* Memberikan jarak antara logo dan teks */
        }
    </style>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- Foto Admin (atau foto pengguna) -->
                <img class="img-profile rounded-circle" src="{{ asset('images/admin.jpg') }}" alt="Admin Avatar" style="width: 30px; height: 30px;">
                <!-- Teks Halo Admin -->
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">MyProfile</span>
            </a>
            <!-- Dropdown - User Information -->
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <!-- Header -->
                <div class="dropdown-item text-center font-weight-bold text-gray-800">
                    <i class="fas fa-user fa-sm fa-fw text-gray-400"></i> Profile 
                </div>
                <hr class="my-1">

                <!-- Nama -->
                <div class="dropdown-item">
                    <i class="fas fa-id-badge fa-sm fa-fw mr-2 text-gray-400"></i>
                    Nama: {{ Auth::user()->name }}
                </div>

                <!-- Email -->
                <div class="dropdown-item">
                    <i class="fas fa-envelope fa-sm fa-fw mr-2 text-gray-400"></i>
                    Email: {{ Auth::user()->email }}
                </div>

                <!-- Role ID -->
                <div class="dropdown-item">
                    <i class="fas fa-user-tag fa-sm fa-fw mr-2 text-gray-400"></i>
                    Role:
                    @switch(Auth::user()->role)
                    @case(1)
                    Staff Penggajian
                    @break
                    @case(2)
                    Direktur
                    @break
                    @case(3)
                    Karyawan
                    @break
                    @default
                    Tidak Diketahui
                    @endswitch
                </div>

                <!-- Last Login -->
                <div class="dropdown-item">
                    <i class="fas fa-clock fa-sm fa-fw mr-2 text-gray-400"></i>
                    Terakhir Login:
                    {{ \Carbon\Carbon::parse(Auth::user()->last_login ?? now())->translatedFormat('d F Y') }}
                </div>


                <div class="dropdown-divider"></div>

                <!-- Logout -->
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>

                <!-- Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

        </li>
    </ul>
</nav>