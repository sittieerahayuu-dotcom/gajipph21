@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">
            <!-- <h4 class="mb-4 text-center text-uppercase fw-bold text-secondary">Form Edit Data Karyawan</h4> -->

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                style="background-color: rgb(207, 204, 157); color: white; border-color: rgb(207, 204, 157);">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div><strong>Berhasil!</strong> {{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Notifikasi Error --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert"
                style="background-color: rgb(207, 204, 157); color: white; border-color: rgb(207, 204, 157);">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div><strong>Oops!</strong> Terdapat kesalahan dalam pengisian form. Silakan periksa kembali isian Anda.</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kolom KIRI -->
                    <div class="col-md-6">
                        {{-- NIK --}}
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-credit-card-2-front-fill"></i></span>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik', $karyawan->nik) }}" required autocomplete="off">
                            </div>
                            @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Karyawan --}}
                        <div class="mb-3">
                            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control @error('nama_karyawan') is-invalid @enderror" name="nama_karyawan" value="{{ old('nama_karyawan', $karyawan->nama_karyawan) }}" required autocomplete="off">
                            </div>
                            @error('nama_karyawan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jabatan --}}
                        <div class="mb-3">
                            <label for="jabatan_id" class="form-label">Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-briefcase-fill"></i></span>
                                <select class="form-control @error('jabatan_id') is-invalid @enderror" name="jabatan_id" required>
                                    @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $jabatan->id ? 'selected' : '' }}>
                                        {{ $jabatan->nama_jabatan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('jabatan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                                    <option value="Laki-Laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom KANAN -->
                    <div class="col-md-6">
                        {{-- Status Pernikahan --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Pernikahan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-heart-fill"></i></span>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="Menikah" {{ old('status', $karyawan->status) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                    <option value="Belum Menikah" {{ old('status', $karyawan->status) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                </select>
                            </div>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jumlah Tanggungan Anak --}}
                        <div class="mb-3">
                            <label for="jumlah_tanggungan_anak" class="form-label">Jumlah Tanggungan Anak</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                                <input type="number" class="form-control @error('jumlah_tanggungan_anak') is-invalid @enderror" name="jumlah_tanggungan_anak" value="{{ old('jumlah_tanggungan_anak', $karyawan->jumlah_tanggungan_anak) }}" required min="0" max="10">
                            </div>
                            @error('jumlah_tanggungan_anak')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kode PTKP --}}
                        <div class="mb-3">
                            <label for="kode_ptkp" class="form-label">Kode PTKP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-code-square"></i></span>
                                <input type="text" class="form-control @error('kode_ptkp') is-invalid @enderror" name="kode_ptkp" value="{{ old('kode_ptkp', $karyawan->kode_ptkp) }}" required>
                            </div>
                            @error('kode_ptkp')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Karyawan --}}
                        <div class="mb-4">
                            <label for="status_karyawan" class="form-label">Status Karyawan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-check-circle-fill"></i></span>
                                <select name="status_karyawan" class="form-control @error('status_karyawan') is-invalid @enderror">
                                    <option value="Aktif" {{ old('status_karyawan', $karyawan->status_karyawan) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status_karyawan', $karyawan->status_karyawan) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            @error('status_karyawan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>

                {{-- Tombol --}}
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success"
                        style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        <i class="bi bi-arrow-repeat me-1"></i> Update
                    </button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary"
                        style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection