@extends('layouts.app')

@section('title', 'Dashboard — Siaga Karhutla')

@section('content')
<div class="page-head">
    <h1>Dashboard</h1>
    <p class="muted">Selamat datang, {{ $user->name }}. Anda masuk sebagai <strong>{{ $user->role->label() }}</strong>.</p>
</div>

<div class="card-grid">
    <div class="card">
        <h3>Peran Anda</h3>
        <p>{{ $user->role->label() }}</p>
        <p class="muted">
            @if ($user->role->value === 'pelapor')
                Anda dapat membuat laporan kejadian dan memantau perkembangan laporan milik Anda.
            @elseif ($user->role->value === 'relawan')
                Anda dapat meninjau laporan, memberikan catatan, dan menandai laporan yang perlu diteruskan.
            @else
                Anda dapat mengelola akun relawan dan data area, mencatat penerusan, serta melihat rekap.
            @endif
        </p>
    </div>
</div>
@endsection
