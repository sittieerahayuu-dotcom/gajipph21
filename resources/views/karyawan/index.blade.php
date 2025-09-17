@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">
            <!-- Tombol Tambah Data + Show Entries + Search -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <!-- Tombol Tambah -->
                <a href="{{ route('karyawan.create') }}" class="btn btn-primary"
                    style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>

            <!-- Show Entries -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <form class="d-flex align-items-center" action="{{ route('karyawan.index') }}" method="GET">
                    <div class="custom-select-container">
                        <label for="entries" class="custom-select-label mb-0">Show:</label>
                        <select name="entries" id="entries"
                            class="form-select form-select-sm custom-select-no-arrow border-0 shadow-none"
                            onchange="this.form.submit()">
                            <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                </form>

                <!-- Form Pencarian -->
                <form class="d-flex align-items-end" action="{{ route('karyawan.index') }}" method="GET" role="search">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="Cari Karyawan"
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit" title="Cari"
                            style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary btn-sm ms-2"
                        style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                    <input type="hidden" name="entries" value="{{ request('entries') }}">
                </form>
            </div>

            <!-- Notifikasi -->
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

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th style="min-width: 150px;">No. Induk Karyawan</th>
                            <th>NIK  / NPWP</th>
                            <th style="min-width: 120px;">Nama Karyawan</th>
                            <th style="min-width: 120px;">Jabatan</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th>Tanggungan Anak</th>
                            <th>Kode PTKP</th>
                            <th>Status Karyawan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyawans as $index => $karyawan)
                        <tr>
                            <td class="text-center">
                                @if ($karyawans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $karyawans->firstItem() + $index }}
                                @else
                                {{ $index + 1 }}
                                @endif
                            </td>
                           <td class="text-center" style="min-width: 150px;">{{ $karyawan->no_induk }}</td>
                            
                            <td class="text-center">{{ $karyawan->nik }}</td>
                            <td class="text-center">{{ $karyawan->nama_karyawan }}</td>
                            <td class="text-center">{{ $karyawan->jabatan?->nama_jabatan ?? 'Tidak Ada' }}</td>
                            <td class="text-center">{{ $karyawan->jenis_kelamin }}</td>
                            <td class="text-center">{{ $karyawan->status }}</td>
                            <td class="text-center">{{ $karyawan->jumlah_tanggungan_anak }}</td>
                            <td class="text-center">{{ $karyawan->kode_ptkp }}</td>
                            <td class="text-center">
                                @if(strtolower($karyawan->status_karyawan) == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-primary btn-sm me-2" style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="10">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 small">
                @if ($karyawans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div>
                    Menampilkan {{ $karyawans->firstItem() }} - {{ $karyawans->lastItem() }} dari {{ $karyawans->total() }} data
                </div>
                <div>
                    <nav>
                        <ul class="pagination pagination-sm m-0">
                            {!! $karyawans->appends(request()->input())->links('pagination::bootstrap-4') !!}
                        </ul>
                    </nav>
                </div>
                @else
                <div>Total data: {{ $karyawans->count() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Styling -->
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

    .custom-select-no-arrow {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: none !important;
        padding-right: 0.75rem;
    }

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