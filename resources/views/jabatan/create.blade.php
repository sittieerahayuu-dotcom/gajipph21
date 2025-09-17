@extends('layouts.app')

@section('title', 'Tambah Jabatan')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">
            <form action="{{ route('jabatans.store') }}" method="POST">
                @csrf

                <!-- Nama Jabatan -->
                <div class="mb-3">
                    <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-briefcase-fill"></i></span>
                        <input type="text" class="form-control" name="nama_jabatan" placeholder="Masukkan nama jabatan" required>
                    </div>
                </div>

                <!-- Gaji Pokok -->
                <div class="mb-3">
                    <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                        <input type="text" class="form-control" name="gaji_pokok" id="gaji_pokok" placeholder="Masukkan gaji pokok" required>
                    </div>
                </div>

                <!-- Tunjangan Makan -->
                <div class="mb-3">
                    <label for="tunjangan_makan" class="form-label">Tunjangan Makan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-egg-fried"></i></span>
                        <input type="text" class="form-control" name="tunjangan_makan" id="tunjangan_makan" placeholder="Masukkan tunjangan makan" required>
                    </div>
                </div>

                <!-- Tunjangan Transportasi -->
                <div class="mb-3">
                    <label for="tunjangan_transportasi" class="form-label">Tunjangan Transportasi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-bus-front-fill"></i></span>
                        <input type="text" class="form-control" name="tunjangan_transportasi" id="tunjangan_transportasi" placeholder="Masukkan tunjangan transportasi" required>
                    </div>
                </div>

                <!-- Info -->
                <div class="alert alert-info small d-flex align-items-center text-dark" role="alert" style="background-color: rgb(187, 183, 128); border-color: rgb(179, 175, 124);">
                    <i class="bi bi-info-circle me-2"></i> BPJS Kesehatan, BPJS Ketenagakerjaan, dan Biaya Jabatan akan dihitung otomatis.
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success" style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('jabatans.index') }}" class="btn btn-secondary" style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                         <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatNumberOnInput(inputId) {
        const input = document.getElementById(inputId);
        input.addEventListener('input', function () {
            let angka = this.value.replace(/\D/g, '');
            this.value = new Intl.NumberFormat('id-ID').format(angka);
        });
    }

    // Format saat ketik
    formatNumberOnInput('gaji_pokok');
    formatNumberOnInput('tunjangan_makan');
    formatNumberOnInput('tunjangan_transportasi');

    // Hapus titik sebelum submit
    document.querySelector('form').addEventListener('submit', function () {
        ['gaji_pokok', 'tunjangan_makan', 'tunjangan_transportasi'].forEach(id => {
            let input = document.getElementById(id);
            input.value = input.value.replace(/\./g, '');
        });
    });
</script>
@endpush
