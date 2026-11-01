@extends('layouts.app')

@section('title', 'Masuk — Siaga Karhutla')

@section('content')
<div class="auth-card">
    <h1>Masuk</h1>
    <p class="muted">Silakan masuk untuk melanjutkan.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group form-check">
            <label><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
    </form>

    <p class="auth-footer">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai Pelapor</a>
    </p>
</div>
@endsection
