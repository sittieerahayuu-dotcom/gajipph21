@extends('layouts.app')

@section('title', 'Rekap Kehadiran Bulanan')

@section('contents')
<div class="container">

    <!-- CARD 1: Filter Bulan, Tahun, Entries -->
    <div class="card border-0 shadow-sm rounded mb-3">
        <div class="card-body">
            <h5 class="card-title border-bottom pb-2 mb-3" style="border-bottom: 2px solid blue;">
                Filter Rekap Kehadiran
            </h5>

            <div class="d-flex flex-wrap gap-3 align-items-end">

                <!-- Form Filter Bulan & Tahun -->
                <form class="d-flex gap-3 align-items-end flex-wrap" method="GET" action="{{ route('kehadiran.rekap') }}" style="flex-grow:1; min-width: 300px;">
                    <div class="flex-grow-1" style="min-width: 120px;">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach(range(1,12) as $b)
                                <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-grow-1" style="min-width: 120px;">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            <option value="">-- Pilih Tahun --</option>
                            @for($year=2020;$year<=2030;$year++)
                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="btn-group" role="group">
                        <button type="submit" class="btn" style="min-width:140px; background-color: rgb(158,154,100); border-color: rgb(158,154,100); color:white;">
                            <i class="fas fa-filter"></i> Tampilkan
                        </button>
                        <a href="{{ route('kehadiran.rekap') }}" class="btn" style="background-color: rgb(80,78,51); border-color: rgb(80,78,51); color:white;">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>
                </form>

                <!-- Entries per page -->
                <!-- <form class="d-flex align-items-center" method="GET" action="{{ route('kehadiran.rekap') }}">
                    <label for="entries" class="me-2 mb-0">Show:</label>
                    <select name="entries" id="entries" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach([5,10,25,50,100] as $num)
                            <option value="{{ $num }}" {{ request('entries') == $num ? 'selected' : '' }}>{{ $num }}</option>
                        @endforeach
                    </select> -->

                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                </form>

            </div>
        </div>
    </div>

    <!-- Filter Message -->
    @if(isset($filterMessage))
    <div class="alert alert-info text-center fw-semibold" style="background-color: rgb(207,204,157); border-color: rgb(207,204,157); color:black;">
        {!! $filterMessage !!}
    </div>
    @endif

    <!-- CARD 2: Tabel Rekap -->
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">

            <table class="table table-bordered ">
                <thead>
    <tr class="text-center">
        <th>No</th>
        <th>Bulan</th> <!-- Tambah kolom Bulan -->
        <th>NIK</th>
        <th>Nama Karyawan</th>
        <th>Jabatan</th>
        <th>Masuk</th>
        <th>Alpha</th>
        <th>Sakit</th>
        <th>Izin</th>
        <th>Cuti</th>
    </tr>
</thead>
<tbody>
   @forelse($rekap as $index => $k)
<tr class="text-center">
    <td>{{ $rekap->firstItem() + $index }}</td>
    <td>{{ \Carbon\Carbon::create()->month($k->bulan)->translatedFormat('F') }}</td> <!-- Bulan -->
    <td>{{ $k->nik }}</td>
    <td class="text-start">{{ $k->nama_karyawan }}</td>
    <td>{{ $k->nama_jabatan }}</td>
    <td>{{ $k->jumlah_masuk }}</td>
    <td>{{ $k->jumlah_alpha }}</td>
    <td>{{ $k->jumlah_sakit }}</td>
    <td>{{ $k->jumlah_izin }}</td>
    <td>{{ $k->jumlah_cuti }}</td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center">Tidak ada data.</td>
</tr>
@endforelse
</tbody>
            </table>

            <!-- Pagination -->
      <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 small flex-wrap gap-2">
                <div>
                    Menampilkan {{ $rekap->firstItem() ?? 0 }} sampai {{ $rekap->lastItem() ?? 0 }} dari {{ $rekap->total() }} data
                </div>
                <div>
                    {!! $rekap->appends(request()->input())->links('pagination::bootstrap-4') !!}
                </div>
            </div>



        </div>
    </div>

</div>
@endsection
