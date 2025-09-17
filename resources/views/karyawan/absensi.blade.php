@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Absensi Mandiri</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('karyawan.absensi.store') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Absen Masuk Hari Ini</button>
    </form>
</div>
@endsection
