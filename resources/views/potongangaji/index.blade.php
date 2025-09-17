@extends('layouts.app')

@section('title', 'Data Potongan Gaji')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">

            <!-- Tombol Tambah -->
            <div class="mb-3">
                <a href="{{ route('potongangaji.create') }}" class="btn btn-success"
                    style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>

            <!-- Show Entries & Pencarian -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <!-- Show Entries -->
                <form class="d-flex align-items-center" action="{{ route('potongangaji.index') }}" method="GET">
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

                <!-- Pencarian -->
                <form class="d-flex align-items-end" action="{{ route('potongangaji.index') }}" method="GET" role="search">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="Cari Potongan"
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit" title="Cari"
                            style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <a href="{{ route('potongangaji.index') }}" class="btn btn-secondary btn-sm ms-2"
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
                        <th class="text-center">No</th>
                        <th class="text-center">Jenis Potongan</th>
                        <th class="text-center">Nilai Potongan (Rp)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($potongans as $index => $p)
                    <tr>
                        <td class="text-center">
                            @if ($potongans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $potongans->firstItem() + $index }}
                            @else
                            {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="text-center">{{ $p->jenis_potongan }}</td>
                        <td class="text-center">Rp {{ number_format($p->nilai_potongan, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('potongangaji.edit', $p->id) }}"
                                    class="btn btn-warning btn-sm me-2" title="Edit"
                                    style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('potongangaji.destroy', $p->id) }}" method="POST"
                                    style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus potongan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Delete"
                                        style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 small">
                @if ($potongans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div>
                    Menampilkan {{ $potongans->firstItem() }} sampai {{ $potongans->lastItem() }} dari {{ $potongans->total() }} entri
                </div>
                <div>
                    {{ $potongans->appends(request()->input())->links('pagination::bootstrap-4') }}
                </div>
                @else
                <div>Total entri: {{ $potongans->count() }}</div>
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
