@extends('layouts.app')

@section('title', 'Input Kehadiran')

@section('contents')
<div class="container d-flex justify-content-center">
    <!-- CARD ABSENSI DI TENGAH -->
    <div class="card shadow-lg rounded-3" style="width: 600px; border: 1px">
        <div class="card-body p-4">
            <h4 class="card-title fw-bold text-center mb-4 pb-2 border-bottom border-2"
                style="color: rgb(80, 78, 51);">
                <i class="bi bi-calendar-check me-2"></i> Input Absen Kehadiran
            </h4>

            <!-- Notifikasi Berhasil -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="background-color: rgb(207, 204, 157); color: white; border-color: rgb(207, 204, 157);">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="background-color: rgb(207, 204, 157); color: white; border-color: rgb(207, 204, 157);">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('kehadiran.store') }}" method="POST" class="row g-3">
                @csrf

                <!-- Pilih Karyawan (admin) -->
                @if(Auth::user()->role != 3)
                <div class="col-12">
                    <label class="form-label fw-semibold">👥 Pilih Karyawan</label>
                    <div class="dropdown-checkbox">
                        <button type="button" class="btn btn-outline-secondary w-100 text-start shadow-sm dropdown-toggle"
                            onclick="this.nextElementSibling.classList.toggle('show')">
                            Pilih Karyawan
                        </button>
                        <div class="dropdown-menu shadow-lg p-3">
                            @foreach($karyawans as $k)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="karyawan_id[]" value="{{ $k->id }}"
                                    id="karyawan_{{ $k->id }}">
                                <label class="form-check-label" for="karyawan_{{ $k->id }}">
                                    {{ $k->nama_karyawan }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <small class="form-text text-muted">✔ Centang karyawan yang hadir.</small>
                </div>
                @else
                <!-- Karyawan biasa -->
                <div class="col-12">
                    <label class="form-label fw-semibold">👤 Nama Karyawan</label>
                    <input type="text" class="form-control shadow-sm" value="{{ Auth::user()->name }}" readonly>
                </div>
                @endif

                <!-- Tanggal -->
                <div class="col-12">
                    <label for="tanggal" class="form-label fw-semibold">📅 Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control shadow-sm"
                        value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Status Kehadiran -->
                <div class="col-12">
                    <label for="status" class="form-label fw-semibold">📌 Status Kehadiran</label>
                    <select name="status" id="status" class="form-select shadow-sm" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="hadir">✅ Hadir</option>
                        <option value="alpha">❌ Alpha</option>
                        <option value="sakit">🤒 Sakit</option>
                        <option value="izin">📝 Izin</option>
                        <option value="cuti">🌴 Cuti</option>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <div class="col-12 d-grid">
                    <button type="submit" class="btn w-100"
                        style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51); color: white;">
                        <i class="bi bi-save me-1"></i> Catat Kehadiran
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Dropdown -->
<style>
    .dropdown-menu {
        display: none;
        border: 1px solid #dee2e6;
        max-height: 200px;
        overflow-y: auto;
        width: 100%;
        background-color: #fff;
        position: absolute;
        z-index: 1050;
        border-radius: .5rem;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-checkbox {
        position: relative;
    }

    .dropdown-checkbox button {
        border-radius: .5rem;
    }

    .form-check {
        margin-bottom: 0.4rem;
    }

    .form-check-input {
        margin-top: 0.3rem;
    }
</style>

<script>
    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.dropdown-checkbox');
        dropdowns.forEach(dd => {
            const menu = dd.querySelector('.dropdown-menu');
            if (!dd.contains(event.target)) menu.classList.remove('show');
        });
    });
</script>
@endsection