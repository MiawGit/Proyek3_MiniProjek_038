@extends('layouts.app')

@section('title', 'Detail Laporan #' . $report->id . ' — Siaga Karhutla')

@section('content')
<div class="page-head row-between">
    <div>
        <h1>Detail Laporan #{{ $report->id }}</h1>
        <p class="muted">Dibuat {{ $report->created_at->format('d M Y H:i') }} oleh {{ $report->user->name }}</p>
    </div>
    <span class="badge {{ $report->status->badgeClass() }}">{{ $report->status->label() }}</span>
</div>

<div class="card">
    <h3>Informasi Kejadian</h3>
    <table class="table detail-table">
        <tr><th>Area / Wilayah</th><td>{{ $report->area->name }}</td></tr>
        <tr><th>Waktu Kejadian</th><td>{{ $report->incident_at->format('d M Y H:i') }}</td></tr>
        <tr><th>Koordinat</th><td>{{ $report->latitude }}, {{ $report->longitude }}</td></tr>
        <tr>
            <th>Kondisi Ditemukan</th>
            <td>{{ $report->description }}</td>
        </tr>
        <tr>
            <th>Bukti Pendukung</th>
            <td>
                @if ($report->evidence_path)
                    <a href="{{ route('reports.evidence', $report) }}" target="_blank">Lihat file bukti</a>
                @else
                    <span class="muted">Tidak ada</span>
                @endif
            </td>
        </tr>
        @if ($report->forwarded_to)
            <tr>
                <th>Diteruskan Kepada</th>
                <td>
                    {{ $report->forwarded_to }}
                    <span class="muted">({{ $report->forwarded_at->format('d M Y H:i') }} oleh {{ $report->forwarder?->name }})</span>
                </td>
            </tr>
        @endif
    </table>

    <div class="action-bar">
        @if ($canUpdate)
            <a href="{{ route('reports.edit', $report) }}" class="btn btn-primary">Perbaiki Laporan</a>
        @elseif (! $isRelawanOrKoordinator && $report->status->value !== 'baru')
            <p class="muted">Laporan sudah diproses dan tidak dapat diubah lagi.</p>
        @endif
        <a href="{{ route('reports.index') }}" class="btn btn-ghost">Kembali</a>
    </div>
</div>

@if ($isRelawanOrKoordinator && count($allowedTransitions))
<div class="card">
    <h3>Tindak Lanjut</h3>
    @foreach ($allowedTransitions as $target)
        <form method="POST" action="{{ route('reports.status.update', $report) }}" class="status-form">
            @csrf
            <input type="hidden" name="status" value="{{ $target->value }}">
            @if ($target->value === 'diteruskan')
                <div class="form-row">
                    <div class="form-group grow">
                        <label for="forwarded_to">Instansi / Pihak Tujuan</label>
                        <input type="text" name="forwarded_to" id="forwarded_to"
                               placeholder="mis. Dinas Pemadam Kebakaran Kab. Bandung" value="{{ old('forwarded_to') }}" required>
                    </div>
                    <div class="form-group self-end">
                        <button type="submit" class="btn btn-primary">Teruskan ke Instansi</button>
                    </div>
                </div>
            @else
                <button type="submit" class="btn btn-primary">Ubah Status Menjadi "{{ $target->label() }}"</button>
            @endif
        </form>
    @endforeach
</div>
@endif

<div class="card">
    <h3>Riwayat Perubahan Status</h3>
    @if ($statusChanges->isEmpty())
        <p class="muted">Belum ada perubahan status.</p>
    @else
        <ul class="timeline">
            @foreach ($statusChanges as $change)
                <li class="timeline-item">
                    <span class="badge {{ \App\Enums\ReportStatus::from($change->from_status)->badgeClass() }}">{{ \App\Enums\ReportStatus::from($change->from_status)->label() }}</span>
                    <span class="arrow">→</span>
                    <span class="badge {{ \App\Enums\ReportStatus::from($change->to_status)->badgeClass() }}">{{ \App\Enums\ReportStatus::from($change->to_status)->label() }}</span>
                    <span class="muted">oleh {{ $change->user?->name ?? 'sistem' }} — {{ $change->created_at->format('d M Y H:i') }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<div class="card">
    <h3>Catatan Peninjauan</h3>
    @if ($reviewNotes->isEmpty())
        <p class="muted">Belum ada catatan.</p>
    @else
        <ul class="note-list">
            @foreach ($reviewNotes as $note)
                <li class="note-item">
                    <p>{{ $note->content }}</p>
                    <span class="muted">— {{ $note->user->name }}, {{ $note->created_at->format('d M Y H:i') }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    @if ($isRelawanOrKoordinator)
        <form method="POST" action="{{ route('reports.notes.store', $report) }}" class="note-form">
            @csrf
            <div class="form-group">
                <label for="content">Tambahkan Catatan</label>
                <textarea name="content" id="content" rows="3" minlength="5"
                          placeholder="Hasil peninjauan lapangan, dugaan penyebab, dsb." required>{{ old('content') }}</textarea>
            </div>
            <button type="submit" class="btn btn-secondary">Simpan Catatan</button>
        </form>
    @endif
</div>
@endsection
