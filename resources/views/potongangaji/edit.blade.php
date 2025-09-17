@extends('layouts.app')

@section('title', 'Edit Potongan Gaji')

@section('contents')
<div class="container">
   <div class="card border-0 shadow-sm rounded">
        <div class="card-body">
            <form action="{{ route('potongangaji.update', $potongan->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Jenis Potongan --}}
                <div class="mb-3">
                    <label class="form-label">Jenis Potongan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-tags-fill"></i></span>
                        <input type="text" name="jenis_potongan" class="form-control"
                            value="{{ old('jenis_potongan', $potongan->jenis_potongan) }}"
                            placeholder="Masukkan jenis potongan" required autocomplete="off">
                    </div>
                </div>

                {{-- Nilai Potongan --}}
                <div class="mb-3">
                    <label class="form-label">Nilai Potongan (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                        <input type="text" name="nilai_potongan" id="nilai_potongan"
                            class="form-control"
                            value="{{ old('nilai_potongan', number_format($potongan->nilai_potongan, 0, ',', '.')) }}"
                            placeholder="Masukkan jumlah potongan" required autocomplete="off">
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success"
                        style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                    <a href="{{ route('potongangaji.index') }}" class="btn btn-secondary"
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
    const input = document.getElementById('nilai_potongan');

    input.addEventListener('input', function () {
        let angka = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(angka);
    });

    // Bersihkan titik sebelum form dikirim
    document.querySelector('form').addEventListener('submit', function () {
        input.value = input.value.replace(/\./g, '');
    });
</script>
@endpush
