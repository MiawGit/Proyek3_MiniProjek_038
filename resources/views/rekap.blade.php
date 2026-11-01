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
