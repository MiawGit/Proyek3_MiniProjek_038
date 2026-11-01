@extends('layouts.app')

@section('title', 'Daftar | Siaga Karhutla')

@section('content')
<div class="auth-card">
    <h1>Daftar Pelapor</h1>
    <p class="muted">Buat akun untuk melaporkan indikasi kebakaran hutan dan lahan.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi (minimal 8 karakter)</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Ulangi Kata Sandi</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
    </form>

    <p class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </p>
</div>
@endsection
