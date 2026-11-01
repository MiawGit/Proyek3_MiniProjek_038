@extends('layouts.app')

@section('title', 'Siaga Karhutla: Pantau & Laporkan Kebakaran Hutan')

@section('content')
<section class="hero">
    <div class="hero-media">
        <img src="{{ asset('images/hero-karhutla.png') }}" alt="Ilustrasi kebakaran hutan dan lahan yang menghanguskan pepohonan serta menimbulkan asap tebal" class="hero-img">
    </div>
    <div class="hero-text">
        <p class="hero-kicker">Lindungi Hutan, Cegah Karhutla</p>
        <h1>Kebakaran Hutan Itu <span class="hero-accent">Berbahaya</span>. Jangan Diam Saja</h1>
        <p class="hero-lead">
            Kebakaran hutan dan lahan (karhutla) tidak hanya menghanguskan pepohonan.
            Asapnya meracuni udara, merusak kesehatan, memusnahkan habitat satwa, dan
            melumpuhkan aktivitas masyarakat. Setiap laporan dini dari warga membantu
            relawan dan koordinator bertindak lebih cepat sebelum api meluas.
        </p>
        <ul class="hero-points">
            <li><strong>Asap beracun</strong> memicu infeksi saluran pernapasan (ISPA) dan gangguan kesehatan serius.</li>
            <li><strong>Habitat satwa musnah</strong>: banyak spesies kehilangan rumah dan sumber makanannya.</li>
            <li><strong>Kerugian besar</strong> bagi lingkungan, ekonomi warga, dan ekosistem yang butuh puluhan tahun pulih.</li>
        </ul>
        <div class="hero-actions">
            @auth
                @if (Auth::user()->role->value === 'pelapor')
                    <a href="{{ route('reports.create') }}" class="btn btn-primary">Buat Laporan</a>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline">Laporan Saya</a>
                @else
                    <a href="{{ route('reports.index') }}" class="btn btn-primary">Lihat Laporan</a>
                    <a href="{{ route('rekap.index') }}" class="btn btn-outline">Rekap</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Buat Laporan</a>
                <a href="{{ route('register') }}" class="btn btn-outline">Daftar sebagai Pelapor</a>
            @endauth
            <a href="#laporan-terbaru" class="btn btn-ghost">Lihat Laporan Terbaru</a>
        </div>
    </div>
</section>

<section class="stats-panel">
    <h2>Gambaran Laporan Kebakaran</h2>
    <p class="stats-sub">Statistik laporan yang masuk dan penanganannya saat ini.</p>
    <div class="dstat-grid">
        <div class="dstat-card">
            <div class="dstat-top">
                <span class="dstat-icon dstat-icon-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg>
                </span>
                <span class="dstat-badge dstat-badge-blue">Total</span>
            </div>
            <span class="dstat-number">{{ $stats['total'] }}</span>
            <span class="dstat-label">Total laporan diterima</span>
        </div>
        <div class="dstat-card">
            <div class="dstat-top">
                <span class="dstat-icon dstat-icon-orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </span>
                <span class="dstat-badge dstat-badge-orange">Proses</span>
            </div>
            <span class="dstat-number">{{ $stats['baru'] }}</span>
            <span class="dstat-label">Menunggu verifikasi</span>
        </div>
        <div class="dstat-card">
            <div class="dstat-top">
                <span class="dstat-icon dstat-icon-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                </span>
                <span class="dstat-badge dstat-badge-green">Selesai</span>
            </div>
            <span class="dstat-number">{{ $stats['selesai'] }}</span>
            <span class="dstat-label">Laporan ditindaklanjuti</span>
        </div>
        <div class="dstat-card">
            <div class="dstat-top">
                <span class="dstat-icon dstat-icon-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </span>
                <span class="dstat-badge dstat-badge-red">Perhatian</span>
            </div>
            <span class="dstat-number">{{ $stats['perlu'] }}</span>
            <span class="dstat-label">Laporan perlu perhatian</span>
        </div>
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
