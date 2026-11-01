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
        @elseif (! $isRelawanOrKoordinator && $report->status->value === 'baru')
            <p class="muted">Laporan ini sudah tidak dapat diubah.</p>
        @endif
        <a href="{{ route('reports.index') }}" class="btn btn-ghost">Kembali</a>
    </div>
</div>
@endsection
