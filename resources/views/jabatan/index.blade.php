@extends('layouts.app')

@section('title', 'Data Jabatan')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">

            <!-- Tombol Tambah Data -->
            <div class="mb-3">
                <a href="{{ route('jabatans.create') }}" class="btn btn-primary"
                    style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>

            <!-- Show Entries dan Pencarian -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <!-- Show Entries -->
                <form class="d-flex align-items-center" action="{{ route('jabatans.index') }}" method="GET">
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
                <form class="d-flex align-items-end" action="{{ route('jabatans.index') }}" method="GET" role="search">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="Cari Jabatan"
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit" title="Cari"
                            style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <a href="{{ route('jabatans.index') }}" class="btn btn-secondary btn-sm ms-2"
                        style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);"
                        title="Refresh">
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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col" class="text-center">Nama Jabatan</th>
                        <th scope="col" class="text-center">Gaji Pokok</th>
                        <th scope="col" class="text-center">Tunjangan Makan</th>
                        <th scope="col" class="text-center">Tunjangan Transport</th>
                        <th scope="col" class="text-center">BPJS (4%)</th>
                        <th scope="col" class="text-center">JKM</th>
                        <th scope="col" class="text-center">JKK</th>
                        <th scope="col" class="text-center">Biaya Jabatan</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jabatans as $index => $jabatan)
                    <tr>
                        <td class="text-center">
                            @if ($jabatans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $jabatans->firstItem() + $index }}
                            @else
                            {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="text-center">{{ $jabatan->nama_jabatan }}</td>
                        <td class="text-end text-nowrap">Rp {{ number_format($jabatan->gaji_pokok, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">Rp {{ number_format($jabatan->tunjangan_makan, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">Rp {{ number_format($jabatan->tunjangan_transportasi, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">Rp {{ number_format($jabatan->tunjangan_bpjs_kesehatan, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">Rp {{ number_format($jabatan->tunjangan_jkm, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">Rp {{ number_format($jabatan->tunjangan_jkk, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">Rp {{ number_format($jabatan->biaya_jabatan, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('jabatans.edit', $jabatan->id) }}" class="btn btn-primary btn-sm me-2"
                                    style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jabatans.destroy', $jabatan->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda ingin menghapus jabatan ini?')" title="Delete"
                                        style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 small">
                @if ($jabatans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div>
                    Menampilkan {{ $jabatans->firstItem() }} - {{ $jabatans->lastItem() }} dari
                    {{ $jabatans->total() }} data
                </div>
                <div>
                    <nav>
                        <ul class="pagination pagination-sm m-0">
                            {!! $jabatans->appends(request()->input())->links('pagination::bootstrap-4') !!}
                        </ul>
                    </nav>
                </div>
                @else
                <div>Total data: {{ $jabatans->count() }}</div>
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