@extends('layouts.app')

@section('title', 'Slip Gaji Karyawan')

@section('contents')
<div class="container d-flex justify-content-center">
    <div class="card border-0 shadow-sm rounded mb-4" style="width: 100%; max-width: 500px;">
        <div class="card-body">
            <h4 class="mb-4 text-center">Slip Gaji Karyawan</h4>

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('slip-gaji.cetak') }}" method="GET">
                <div class="form-group mb-3">
                    <label for="bulan">Pilih Bulan</label>
                    <select name="bulan" id="bulan" class="form-control" required>
                        <option value="">-- Pilih Bulan --</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                            @endfor
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="tahun">Pilih Tahun</label>
                    <select name="tahun" id="tahun" class="form-control" required>
                        <option value="">-- Pilih Tahun --</option>
                        @for ($y = 2020; $y <= 2030; $y++)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                            {{ $y }}
                            </option>
                            @endfor
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="nama_karyawan">Pilih Nama Karyawan</label>
                    <select name="nama_karyawan" id="nama_karyawan" class="form-control" required>
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($karyawans as $karyawan)
                        <option value="{{ $karyawan->nama_karyawan }}" {{ request('nama_karyawan') == $karyawan->nama_karyawan ? 'selected' : '' }}>
                            {{ $karyawan->no_induk }} - {{ $karyawan->nama_karyawan }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group d-grid gap-2">
                    <button type="submit" name="action" value="lihat" class="btn btn-secondary mb-2" style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                        Lihat Slip Gaji
                    </button>
                    <button type="submit" name="action" value="cetak" class="btn btn-primary" style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        Cetak Slip Gaji
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection