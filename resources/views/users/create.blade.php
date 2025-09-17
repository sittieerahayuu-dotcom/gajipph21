@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- Nama --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                            placeholder="Masukkan nama pengguna" required autocomplete="off">
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                            placeholder="Masukkan email aktif" required>
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
                        <span class="input-group-text" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye" id="toggle-password-icon"></i>
                        </span>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                        <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                            <i class="bi bi-eye" id="toggle-confirm-icon"></i>
                        </span>
                    </div>
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                        <select name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Staff Penggajian</option>
                            <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Direktur</option>
                            <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>Karyawan</option>
                        </select>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success"
                        style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary"
                        style="background-color: rgb(158, 154, 100); border-color: rgb(158, 154, 100);">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Script Toggle Mata --}}
<script>
    function togglePassword(id, el) {
        const input = document.getElementById(id);
        const icon = el.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection