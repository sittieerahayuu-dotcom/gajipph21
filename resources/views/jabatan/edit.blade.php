@extends('layouts.app')

@section('title', 'Edit Jabatan')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">
            <form action="{{ route('jabatans.update', $jabatan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kolom KIRI -->
                    <div class="col-md-6">
                        <!-- Nama Jabatan -->
                        <div class="mb-3">
                            <label class="form-label">Nama Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-briefcase-fill"></i></span>
                                <input type="text" class="form-control" name="nama_jabatan"
                                    value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" required>
                            </div>
                        </div>

                        <!-- Gaji Pokok -->
                        <div class="mb-3">
                            <label class="form-label">Gaji Pokok</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                                <input type="text" id="gaji_pokok" class="form-control" name="gaji_pokok"
                                    value="{{ number_format(old('gaji_pokok', $jabatan->gaji_pokok), 0, ',', '.') }}" required>
                            </div>
                        </div>

                        <!-- Tunjangan Makan -->
                        <div class="mb-3">
                            <label class="form-label">Tunjangan Makan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-egg-fried"></i></span>
                                <input type="text" id="tunjangan_makan" class="form-control" name="tunjangan_makan"
                                    value="{{ number_format(old('tunjangan_makan', $jabatan->tunjangan_makan), 0, ',', '.') }}" required>
                            </div>
                        </div>

                        <!-- Tunjangan Transportasi -->
                        <div class="mb-3">
                            <label class="form-label">Tunjangan Transportasi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-bus-front-fill"></i></span>
                                <input type="text" id="tunjangan_transportasi" class="form-control" name="tunjangan_transportasi"
                                    value="{{ number_format(old('tunjangan_transportasi', $jabatan->tunjangan_transportasi), 0, ',', '.') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom KANAN -->
                    <div class="col-md-6">
                        <!-- Tunjangan BPJS Kesehatan -->
                        <div class="mb-3">
                            <label class="form-label">Tunjangan BPJS Kesehatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-heart-pulse"></i></span>
                                <input type="text" id="tunjangan_bpjs_kesehatan" class="form-control" name="tunjangan_bpjs_kesehatan"
                                    value="{{ number_format(old('tunjangan_bpjs_kesehatan', $jabatan->tunjangan_bpjs_kesehatan), 0, ',', '.') }}" required>
                            </div>
                        </div>

                        <!-- Tunjangan JKM -->
                        <div class="mb-3">
                            <label class="form-label">Tunjangan JKM</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hospital"></i></span>
                                <input type="text" id="tunjangan_jkm" class="form-control" name="tunjangan_jkm"
                                    value="{{ number_format(old('tunjangan_jkm', $jabatan->tunjangan_jkm), 0, ',', '.') }}" required>
                            </div>
                        </div>

                        <!-- Tunjangan JKK -->
                        <div class="mb-3">
                            <label class="form-label">Tunjangan JKK</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-plus"></i></span>
                                <input type="text" id="tunjangan_jkk" class="form-control" name="tunjangan_jkk"
                                    value="{{ number_format(old('tunjangan_jkk', $jabatan->tunjangan_jkk), 0, ',', '.') }}" required>
                            </div>
                        </div>

                        <!-- Biaya Jabatan -->
                        <div class="mb-3">
                            <label class="form-label">Biaya Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-percent"></i></span>
                                <input type="text" id="biaya_jabatan" class="form-control" name="biaya_jabatan"
                                    value="{{ number_format(old('biaya_jabatan', $jabatan->biaya_jabatan), 0, ',', '.') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success"
                        style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        <i class="bi bi-arrow-repeat me-1"></i> Update
                    </button>
                    <a href="{{ route('jabatans.index') }}" class="btn btn-secondary"
                        style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
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
    function formatRupiah(inputId) {
        const input = document.getElementById(inputId);
        input.addEventListener('input', function () {
            let value = this.value.replace(/\D/g, '');
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    }

    const fieldIds = [
        'gaji_pokok',
        'tunjangan_makan',
        'tunjangan_transportasi',
        'tunjangan_bpjs_kesehatan',
        'tunjangan_jkm',
        'tunjangan_jkk',
        'biaya_jabatan'
    ];

    fieldIds.forEach(id => formatRupiah(id));

    // Bersihkan titik sebelum submit
    document.querySelector('form').addEventListener('submit', function () {
        fieldIds.forEach(id => {
            const input = document.getElementById(id);
            input.value = input.value.replace(/\./g, '');
        });
    });
</script>
@endpush
