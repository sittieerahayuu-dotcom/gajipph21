@extends('layouts.app')

@section('title', 'Absensi Kehadiran')

@section('contents')
<div class="container">

    <!-- CARD 1: Filter Bulan, Tahun, dan Tombol Tampilkan & Hapus -->
    <div class="card border-0 shadow-sm rounded mb-3">
        <div class="card-body">
            <h5 class="card-title border-bottom pb-2 mb-3" style="border-bottom: 2px solid blue;">
                Filter Absensi Kehadiran
            </h5>

            <div class="d-flex flex-wrap gap-3 align-items-end">
                <!-- Form Filter Bulan dan Tahun -->
                <form class="d-flex gap-3 align-items-end flex-wrap" method="GET" action="{{ route('kehadiran.index') }}" style="flex-grow:1; min-width: 300px;">
                    <div class="flex-grow-1" style="min-width: 120px;">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach(range(1, 12) as $b)
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
                            @for ($year = 2020; $year <= 2030; $year++)
                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                                </option>
                                @endfor
                        </select>
                    </div>


                    <div class="btn-group" role="group">
                        <button type="submit" class="btn"
                            style="min-width: 140px; background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100); color: white;">
                            <i class="fas fa-filter"></i> Filter Data
                        </button>

                        <a href="{{ route('kehadiran.index') }}" class="btn"
                            style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51); color: white;" title="Reset">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>

                </form>

                <!-- Tombol Tambah Kehadiran -->
                @can('isKaryawan')
                <div class="col-md-3 text-end">
                    <a href="{{ route('kehadiran.create') }}" class="btn btn-success w-100 mt-4" style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        + Absen Kehadiran
                    </a>
                </div>
 @endcan
                <!-- Form Hapus Bulanan -->
                 <!-- @can('isStaffPenggajian')
                <form method="POST" action="{{ route('kehadiran.hapus-bulanan') }}" onsubmit="return confirm('Yakin ingin menghapus data bulan ini?')" style="min-width: 180px;">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                    <button type="submit" class="btn btn-danger w-100 mt-4" style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                        <i class="fas fa-trash-alt"></i> Hapus Data
                    </button>
                </form>
                @endcan -->
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI FILTER -->
    @if(isset($filterMessage))
    <div class="alert alert-info text-center fw-semibold" style="background-color: rgb(207, 204, 157); border-color: rgb(207, 204, 157); color: black;">
        {!! $filterMessage !!}
    </div>
    @endif

    <!-- CARD 2: Search & Tabel -->
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">

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
            <!-- Container gabungan untuk Show Entries dan Search supaya sejajar -->
            <div class="d-flex justify-content-between mb-3 align-items-center flex-wrap gap-3">
                <form class="d-flex align-items-center" action="{{ route('kehadiran.index') }}" method="GET">
                    <div class="custom-select-container">
                        <label for="entries" class="custom-select-label mb-0">Show:</label>
                        <select
                            name="entries"
                            id="entries"
                            class="form-select form-select-sm custom-select-no-arrow border-0 shadow-none"
                            onchange="this.form.submit()">
                            <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    {{-- Hidden filter fields untuk mempertahankan filter --}}
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                </form>

                <!-- Search -->
                  
                <form class="d-flex" action="{{ route('kehadiran.index') }}" method="GET" style="min-width: 250px;">
                     @can('isStaffPenggajian')
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="Cari Kehadiran" value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit" title="Cari" style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <a href="{{ route('kehadiran.index') }}" class="btn btn-secondary btn-sm ms-2" style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                      @endcan
                    {{-- Untuk tetap membawa filter bulan, tahun, entries saat submit form search --}}
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                    <input type="hidden" name="entries" value="{{ request('entries') }}">
                </form>

            </div>

            <!-- Tabel Kehadiran -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">NIK / NPWP</th>
                        <th class="text-center">Nama Karyawan</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Masuk</th>
                        <th class="text-center">Alpha</th>
                        <th class="text-center">Sakit</th>
                        <th class="text-center">Izin</th>
                        <th class="text-center">Cuti</th>
                        @can('isStaffPenggajian')
                        <th class="text-center">Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kehadirans as $index => $kehadiran)
                    <tr>
                        <td class="text-center">{{ $kehadirans->firstItem() + $index }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($kehadiran->tanggal)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $kehadiran->nik }}</td>
                        <td>{{ $kehadiran->nama_karyawan }}</td>
                        <td class="text-center">{{ $kehadiran->nama_jabatan }}</td>
                        <td class="text-center">{{ $kehadiran->jumlah_masuk }}</td>
                        <td class="text-center">{{ $kehadiran->jumlah_alpha }}</td>
                        <td class="text-center">{{ $kehadiran->jumlah_sakit }}</td>
                        <td class="text-center">{{ $kehadiran->jumlah_izin }}</td>
                        <td class="text-center">{{ $kehadiran->jumlah_cuti }}</td>
                        @can('isStaffPenggajian')
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('kehadiran.edit', $kehadiran->id) }}" class="btn btn-warning btn-sm" title="Edit" style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('kehadiran.destroy', $kehadiran->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" >
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Hapus" style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endcan
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <style>
                .pagination .page-item.active .page-link {
                    background-color: rgb(80, 78, 51);
                    border-color: rgb(80, 78, 51);
                }

                .pagination .page-link {
                    color: rgb(80, 78, 51);
                }

                .pagination .page-link:hover {
                    background-color: rgba(80, 78, 51, 0.8);
                    border-color: rgb(80, 78, 51);
                    color: white;
                }
            </style>


            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 small flex-wrap gap-2">
                <div>
                    Menampilkan {{ $kehadirans->firstItem() ?? 0 }} sampai {{ $kehadirans->lastItem() ?? 0 }} dari {{ $kehadirans->total() }} data
                </div>
                <div>
                    {!! $kehadirans->appends(request()->input())->links('pagination::bootstrap-4') !!}
                </div>
            </div>

        </div>
    </div>
</div>


<style>
    /* <!-- Show entries --> */
    .custom-select-no-arrow {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: none !important;
        padding-right: 0.75rem;
    }

    /* Tambahkan border dan style modern */
    .custom-select-container {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 2px 8px;
        display: flex;
        align-items: center;
        background-color: white;
    }

    .custom-select-label {
        margin-right: 8px;
        font-size: 14px;
        color: #333;
        white-space: nowrap;
    }

    .custom-select-no-arrow:focus {
        box-shadow: none;
        border-color: #86b7fe;
        outline: none;
    }
</style>
@endsection