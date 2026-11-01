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

<div class="card filter-card">
    <form method="GET" action="{{ route('reports.index') }}" class="filter-form">
        <div class="form-group grow">
            <label for="q">Kata Kunci</label>
            <input type="text" name="q" id="q" placeholder="Cari deskripsi laporan..." value="{{ $filters['q'] ?? '' }}">
        </div>
        <div class="form-group">
            <label for="area_id">Area</label>
            <select name="area_id" id="area_id">
                <option value="">Semua Area</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected(($filters['area_id'] ?? '') == $area->id)>{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="">Semua Status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') == $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group self-end">
            <button type="submit" class="btn btn-secondary">Terapkan Filter</button>
        </div>
    </form>
</div>

@if ($reports->isEmpty())
    <div class="card">
        <p class="muted">Tidak ada laporan yang cocok.</p>
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
