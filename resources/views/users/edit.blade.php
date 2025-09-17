@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('contents')
<div class="container">
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $user->email) }}" placeholder="Masukkan email aktif" required>
                    </div>
                </div>

                {{-- Password (Opsional) --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Isi jika ingin ganti password" required>
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
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
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
                        <select class="form-select" id="role" name="role" required>
                            <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Staff Penggajian</option>
                            <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Direktur</option>
                            <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>Karyawan</option>
                        </select>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success"
                        style="background-color: rgb(80, 78, 51); border-color: rgb(80, 78, 51);">
                        <i class="bi bi-arrow-repeat me-1"></i> Update
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