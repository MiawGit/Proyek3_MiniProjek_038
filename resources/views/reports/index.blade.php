@extends('layouts.app')

@section('title', $isPelapor ? 'Laporan Saya — Siaga Karhutla' : 'Daftar Laporan — Siaga Karhutla')

@section('content')
<div class="page-head row-between">
    <div>
        <h1>{{ $isPelapor ? 'Laporan Saya' : 'Daftar Laporan' }}</h1>
        <p class="muted">{{ $reports->total() }} laporan ditemukan.</p>
    </div>
    @if ($isPelapor)
        <a href="{{ route('reports.create') }}" class="btn btn-primary">+ Buat Laporan</a>
    @endif
</div>

@if ($reports->isEmpty())
    <div class="card">
        <p class="muted">Belum ada laporan.</p>
    </div>
@else
    <div class="card table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Area</th>
                    @unless ($isPelapor) <th>Pelapor</th> @endunless
                    <th>Waktu Kejadian</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->area->name }}</td>
                        @unless ($isPelapor)
                            <td>{{ $report->user->name }}</td>
                        @endunless
                        <td>{{ $report->incident_at->format('d M Y H:i') }}</td>
                        <td><span class="badge {{ $report->status->badgeClass() }}">{{ $report->status->label() }}</span></td>
                        <td class="text-right">
                            <a href="{{ route('reports.show', $report) }}" class="btn btn-secondary btn-sm">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrap">
            {{ $reports->links() }}
        </div>
    </div>
@endif
@endsection
