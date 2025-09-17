@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                style="background-color: rgb(207, 204, 157); color: white; border-color: rgb(207, 204, 157);">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div><strong>Berhasil!</strong> {{ session('success') }}</div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- Notifikasi Error --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert"
                style="background-color: rgb(207, 204, 157); color: white; border-color: rgb(207, 204, 157);">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div><strong>Oops!</strong> Terdapat kesalahan dalam pengisian form. Silakan periksa kembali isian Anda.</div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif


            <form action="{{ route('karyawan.store') }}" method="POST">
                @csrf

                <!-- NIK -->
                <div class="row">
                    <!-- Kolom KIRI -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-credit-card-2-front-fill"></i></span>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="Masukkan NIK karyawan" maxlength="16" value="{{ old('nik') }}" required>
                            </div>
                            @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Karyawan -->
                        <div class="mb-3">
                            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control @error('nama_karyawan') is-invalid @enderror" id="nama_karyawan" name="nama_karyawan" placeholder="Masukkan nama karyawan" value="{{ old('nama_karyawan') }}" required>
                            </div>
                            @error('nama_karyawan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div class="mb-3">
                            <label for="jabatan_id" class="form-label">Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-briefcase-fill"></i></span>
                                <select class="form-control @error('jabatan_id') is-invalid @enderror" id="jabatan_id" name="jabatan_id" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                                        {{ $jabatan->nama_jabatan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('jabatan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom KANAN -->
                    <div class="col-md-6">
                        <!-- Status Pernikahan -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Pernikahan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-heart-fill"></i></span>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Menikah" {{ old('status') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                    <option value="Belum Menikah" {{ old('status') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                </select>
                            </div>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggungan Anak -->
                        <div class="mb-3">
                            <label for="jumlah_tanggungan_anak" class="form-label">Jumlah Tanggungan Anak</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                                <input type="number" class="form-control @error('jumlah_tanggungan_anak') is-invalid @enderror" id="jumlah_tanggungan_anak" name="jumlah_tanggungan_anak" placeholder="Masukkan jumlah tanggungan anak" value="{{ old('jumlah_tanggungan_anak') }}" min="0" max="10" required>
                            </div>
                            @error('jumlah_tanggungan_anak')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Karyawan -->
                        <div class="mb-3">
                            <label for="status_karyawan" class="form-label">Status Karyawan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-check-circle-fill"></i></span>
                                <select class="form-control @error('status_karyawan') is-invalid @enderror" id="status_karyawan" name="status_karyawan" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Aktif" {{ old('status_karyawan') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status_karyawan') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            @error('status_karyawan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <!-- Info BPJS & Tarif -->
                        <div class="alert alert-info small d-flex align-items-center text-dark" role="alert" style="background-color: rgb(187, 183, 128); border-color: rgb(179, 175, 124);">
                            <i class="bi bi-info-circle me-2"></i> Kode PTKP akan keluar secara otomatis.
                        </div>
                    </div>
                </div>
                <!-- Tombol Simpan & Batal -->
                <br>
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success" style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary" style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>




            </form>
        </div>
    </div>
</div>
@endsection