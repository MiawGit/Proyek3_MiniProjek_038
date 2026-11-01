@extends('layouts.app')

@section('title', 'Siaga Karhutla — Pantau & Laporkan Kebakaran Hutan')

@section('content')
<section class="hero">
    <div class="hero-text">
        <p class="hero-kicker">Lindungi Hutan, Cegah Karhutla</p>
        <h1>Kebakaran Hutan Itu <span class="hero-accent">Berbahaya</span> — Jangan Diam Saja</h1>
        <p class="hero-lead">
            Kebakaran hutan dan lahan (karhutla) tidak hanya menghanguskan pepohonan.
            Asapnya meracuni udara, merusak kesehatan, memusnahkan habitat satwa, dan
            melumpuhkan aktivitas masyarakat. Setiap laporan dini dari warga membantu
            relawan dan koordinator bertindak lebih cepat sebelum api meluas.
        </p>
        <ul class="hero-points">
            <li><strong>Asap beracun</strong> memicu infeksi saluran pernapasan (ISPA) dan gangguan kesehatan serius.</li>
            <li><strong>Habitat satwa musnah</strong> — banyak spesies kehilangan rumah dan sumber makanannya.</li>
            <li><strong>Kerugian besar</strong> bagi lingkungan, ekonomi warga, dan ekosistem yang butuh puluhan tahun pulih.</li>
        </ul>
        <div class="hero-actions">
            @auth
                @if (Auth::user()->role->value === 'pelapor')
                    <a href="{{ route('reports.create') }}" class="btn btn-primary">Buat Laporan</a>
                @else
                    <a href="{{ route('reports.index') }}" class="btn btn-primary">Lihat Laporan</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Buat Laporan</a>
                <a href="{{ route('register') }}" class="btn btn-outline">Daftar sebagai Pelapor</a>
            @endauth
            <a href="#laporan-terbaru" class="btn btn-ghost">Lihat Laporan Terbaru</a>
        </div>
    </div>
    <div class="hero-media">
        <img src="{{ asset('images/hero-karhutla.png') }}" alt="Ilustrasi kebakaran hutan dan lahan yang menghanguskan pepohonan serta menimbulkan asap tebal" class="hero-img">
    </div>
</section>

<section class="stat-grid landing-stats">
    <div class="card stat-card">
        <span class="stat-number">{{ $stats['total'] }}</span>
        <span class="stat-label">Laporan Diterima</span>
    </div>
    <div class="card stat-card">
        <span class="stat-number">{{ $stats['selesai'] }}</span>
        <span class="stat-label">Laporan Selesai Ditangani</span>
    </div>
    <div class="card stat-card">
        <span class="stat-number">{{ $stats['perlu'] }}</span>
        <span class="stat-label">Perlu Tindak Lanjut</span>
    </div>
    <div class="card stat-card">
        <span class="stat-number">{{ $stats['area'] }}</span>
        <span class="stat-label">Area Terpantau</span>
    </div>
</section>

<section id="laporan-terbaru" class="landing-section">
    <div class="section-head">
        <h2>Laporan Terbaru dari Warga</h2>
        <p class="muted">
            Berikut kejadian yang sedang dilaporkan dan dipantau. Demi privasi, nama
            pelapor dan titik koordinat tidak ditampilkan di halaman publik.
        </p>
    </div>

    @if ($reports->isEmpty())
        <div class="card empty-state">
            <p>Belum ada laporan masuk. Jadilah yang pertama melaporkan kejadian di sekitar Anda.</p>
        </div>
    @else
        <div class="report-grid">
            @foreach ($reports as $report)
                @php($cardUrl = auth()->check() ? route('reports.show', $report) : null)
                <article class="card report-card">
                    <div class="report-card-top">
                        <span class="badge {{ $report->status->badgeClass() }}">{{ $report->status->label() }}</span>
                        <span class="report-card-date">{{ $report->incident_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <h3 class="report-card-area">{{ $report->area->name }}</h3>
                    <p class="report-card-desc">{{ \Illuminate\Support\Str::limit($report->description, 110) }}</p>
                    @if ($cardUrl)
                        <a href="{{ $cardUrl }}" class="report-card-link">Lihat detail →</a>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</section>

@guest
<section class="cta-banner card">
    <h2>Ingin Ikut Melapor atau Memantau?</h2>
    <p>
        Membuat laporan kejadian hanya membutuhkan satu akun. Daftar sebagai pelapor,
        lalu kirim laporan lengkap dengan lokasi dan bukti foto.
    </p>
    <div class="hero-actions">
        <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-outline">Daftar</a>
    </div>
</section>
@endguest
@endsection
