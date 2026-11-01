<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Siaga Karhutla')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@auth
<header class="navbar">
    <div class="container navbar-inner">
        <a href="{{ route('dashboard') }}" class="brand">Siaga Karhutla</a>
        <nav class="navbar-nav">
            @if (Auth::user()->role->value === 'pelapor')
                <a href="{{ route('reports.index') }}">Laporan Saya</a>
                <a href="{{ route('reports.create') }}">Buat Laporan</a>
            @else
                <a href="{{ route('reports.index') }}">Daftar Laporan</a>
            @endif
        </nav>
        <div class="navbar-right">
            <span class="user-info">
                {{ Auth::user()->name }}
                <span class="badge badge-role">{{ Auth::user()->role->label() }}</span>
            </span>
            <form method="POST" action="{{ route('logout') }}" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">Keluar</button>
            </form>
        </div>
    </div>
</header>
@endauth

<main class="container">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>
