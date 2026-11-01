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

    <div class="card">
        <h3>Ringkasan Area</h3>
        <div class="form-group">
            <label for="summary_area">Pilih Area</label>
            <select id="summary_area">
                <option value="">— Pilih area —</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div id="area_summary" class="area-summary">
            <p class="muted">Pilih area untuk melihat ringkasan laporan.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('summary_area').addEventListener('change', function () {
    const box = document.getElementById('area_summary');
    if (!this.value) {
        box.innerHTML = '<p class="muted">Pilih area untuk melihat ringkasan laporan.</p>';
        return;
    }
    box.innerHTML = '<p class="muted">Memuat...</p>';
    fetch('/areas/' + this.value + '/summary', { headers: { 'Accept': 'application/json' } })
        .then(r => {
            if (!r.ok) throw new Error('Gagal memuat');
            return r.json();
        })
        .then(data => {
            let html = '<p><strong>' + data.area + '</strong> — ' + data.total + ' laporan</p><ul class="summary-list">';
            const labels = {
                baru: 'Baru', diperiksa: 'Diperiksa', perlu_tindak_lanjut: 'Perlu Tindak Lanjut',
                diteruskan: 'Diteruskan', selesai: 'Selesai'
            };
            for (const [key, label] of Object.entries(labels)) {
                html += '<li>' + label + ': <strong>' + (data.summary[key] ?? 0) + '</strong></li>';
            }
            html += '</ul>';
            if (data.reports) {
                html += '<p class="muted">Laporan terakhir di area ini:</p><ul class="summary-list">';
                data.reports.slice(0, 5).forEach(r => {
                    html += '<li>#' + r.id + ' (' + r.status + ') — ' + r.incident_at + ' oleh ' + r.reported_by + '</li>';
                });
                html += '</ul>';
            }
            box.innerHTML = html;
        })
        .catch(() => { box.innerHTML = '<p class="muted">Gagal memuat ringkasan.</p>'; });
});
</script>
@endpush
