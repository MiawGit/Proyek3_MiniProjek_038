@extends('layouts.app')

@section('title', 'Rekap Laporan | Siaga Karhutla')

@section('content')
<div class="page-head">
    <h1>Rekap Laporan</h1>
    <p class="muted">Ringkasan laporan kebakaran hutan dan lahan.</p>
</div>

<div class="card filter-card">
    <form method="GET" action="{{ route('rekap.index') }}" class="filter-form">
        <div class="form-group">
            <label for="area_id">Area</label>
            <select name="area_id" id="area_id">
                <option value="">Semua Area</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected($selectedArea == $area->id)>{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group self-end">
            <button type="submit" class="btn btn-secondary">Tampilkan</button>
        </div>
    </form>
</div>

<div class="card-grid stat-grid">
    <div class="card stat-card">
        <span class="stat-number">{{ $cards['total'] }}</span>
        <span class="muted">Total Laporan</span>
    </div>
    <div class="card stat-card">
        <span class="stat-number">{{ $cards['perlu'] }}</span>
        <span class="muted">Perlu Tindak Lanjut</span>
    </div>
    <div class="card stat-card">
        <span class="stat-number">{{ $cards['diteruskan'] }}</span>
        <span class="muted">Diteruskan</span>
    </div>
    <div class="card stat-card">
        <span class="stat-number">{{ $cards['selesai'] }}</span>
        <span class="muted">Selesai</span>
    </div>
</div>

<div class="card">
    <h3>Ringkasan Area</h3>
    <p class="muted">Pilih area untuk melihat ringkasan laporan tanpa memuat ulang halaman.</p>
    <div class="form-group">
        <label for="summary_area">Pilih Area</label>
        <select id="summary_area">
            <option value="">Pilih area</option>
            @foreach ($areas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
    </div>
    <div id="area_summary" class="area-summary">
        <p class="muted">Pilih area untuk melihat ringkasan laporan.</p>
    </div>
</div>

<div class="card table-card">
    <h3>Rekap per Area × Status</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Area</th>
                @foreach ($statuses as $status)
                    <th>{{ $status->label() }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    @foreach ($statuses as $status)
                        <td>{{ $row['counts'][$status->value] }}</td>
                    @endforeach
                    <td><strong>{{ $row['total'] }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
            let html = '<p><strong>' + data.area + '</strong>: ' + data.total + ' laporan</p><ul class="summary-list">';
            const labels = {
                baru: 'Baru', diperiksa: 'Diperiksa', perlu_tindak_lanjut: 'Perlu Tindak Lanjut',
                diteruskan: 'Diteruskan', selesai: 'Selesai'
            };
            for (const [key, label] of Object.entries(labels)) {
                html += '<li>' + label + ': <strong>' + (data.summary[key] ?? 0) + '</strong></li>';
            }
            html += '</ul>';
            if (data.reports) {
                html += '<p class="muted">Laporan di area ini:</p><ul class="summary-list">';
                data.reports.slice(0, 5).forEach(r => {
                    html += '<li>#' + r.id + ' (' + r.status + ') ' + r.incident_at + ' oleh ' + r.reported_by + '</li>';
                });
                html += '</ul>';
            }
            box.innerHTML = html;
        })
        .catch(() => { box.innerHTML = '<p class="muted">Gagal memuat ringkasan.</p>'; });
});
</script>
@endpush
