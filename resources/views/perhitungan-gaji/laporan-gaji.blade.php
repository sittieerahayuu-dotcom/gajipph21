@extends('layouts.app')

@section('title', 'Laporan Perhitungan Gaji')

@section('contents')
<div class="container d-flex justify-content-center">
    <div class="card border-0 shadow-sm rounded mb-4" style="width: 100%; max-width: 500px;">
        <div class="card-body">
            <h4 class="mb-4 text-center">Laporan Perhitungan Gaji Karyawan</h4>

            {{-- Tampilkan error jika ada --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('perhitungan-gaji.cetak') }}" method="GET">
                <div class="form-group mb-3">
                    <label for="bulan">Pilih Bulan</label>
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">-- Pilih Bulan --</option>
                        <option value="">Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('bulan', request('bulan')) == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                            @endfor
                    </select>
                    @error('bulan')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="tahun">Pilih Tahun</label>
                    <select name="tahun" id="tahun" class="form-control" required>
                        <option value="">-- Pilih Tahun --</option>
                        @for ($year = 2020; $year <= 2030; $year++)
                            <option value="{{ $year }}" {{ old('tahun', request('tahun')) == $year ? 'selected' : '' }}>
                            {{ $year }}
                            </option>
                            @endfor
                    </select>
                    @error('tahun')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tambahan menu pilih karyawan --}}
                <div class="form-group mb-3">
                    <label for="karyawan_id">Pilih Karyawan</label>
                    <select name="karyawan_id" id="karyawan_id" class="form-control">
                        <option value="">-- Pilih Karyawan --</option>
                        <option value="">Semua Karyawan</option>
                        @foreach ($karyawans as $karyawan)
                        <option value="{{ $karyawan->id }}" {{ request('karyawan_id') == $karyawan->id ? 'selected' : '' }}>
                            {{ $karyawan->no_induk }} - {{ $karyawan->nama_karyawan }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group d-grid gap-2 mb-2">
                    <button type="submit" name="preview" value="true" class="btn btn-warning"
                        style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                        Lihat Laporan
                    </button>
                </div>

                <div class="form-group d-grid gap-2">
                    <button type="submit" class="btn btn-success"
                        style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection