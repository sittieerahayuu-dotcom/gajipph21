@extends('layouts.app')

@section('title', 'Edit Kehadiran')

@section('contents')
<div class="container d-flex justify-content-center">
    <!-- CARD EDIT ABSENSI DI TENGAH -->
    <div class="card shadow-lg rounded-3" style="width: 600px;">
        <div class="card-body p-4">
            <h4 class="card-title fw-bold text-center mb-4 pb-2 border-bottom border-2"
                style="color: rgb(80, 78, 51);">
                <i class="bi bi-pencil-square me-2"></i> Edit Kehadiran
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

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('kehadiran.update', $kehadiran->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <!-- Karyawan -->
                <div class="col-12">
                    <label class="form-label fw-semibold">👤 Nama Karyawan</label>
                    <input type="text" class="form-control shadow-sm" value="{{ $kehadiran->karyawan->nama_karyawan ?? '-' }}" readonly>
                </div>
                <!-- Tanggal -->
                <div class="col-12">
                    <label for="tanggal" class="form-label fw-semibold">📅 Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control shadow-sm"
                        value="{{ old('tanggal', $kehadiran->tanggal) }}" required>
                </div>

                <!-- Status Kehadiran -->
                <div class="col-12">
                    <label for="status" class="form-label fw-semibold">📌 Status Kehadiran</label>
                    <select name="status" id="status" class="form-select shadow-sm" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="hadir" {{ $kehadiran->status == 'hadir' ? 'selected' : '' }}>✅ Hadir</option>
                        <option value="alpha" {{ $kehadiran->status == 'alpha' ? 'selected' : '' }}>❌ Alpha</option>
                        <option value="sakit" {{ $kehadiran->status == 'sakit' ? 'selected' : '' }}>🤒 Sakit</option>
                        <option value="izin" {{ $kehadiran->status == 'izin' ? 'selected' : '' }}>📝 Izin</option>
                        <option value="cuti" {{ $kehadiran->status == 'cuti' ? 'selected' : '' }}>🌴 Cuti</option>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <div class="col-12 d-grid">
                    <button type="submit" class="btn w-100"
                        style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51); color: white;">
                        <i class="bi bi-save me-1"></i> Update Kehadiran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection