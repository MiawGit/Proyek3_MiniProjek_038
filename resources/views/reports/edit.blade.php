@extends('layouts.app')

@section('title', 'Perbaiki Laporan #' . $report->id . ' | Siaga Karhutla')

@section('content')
<div class="page-head">
    <h1>Perbaiki Laporan #{{ $report->id }}</h1>
    <p class="muted">Laporan hanya dapat diubah selama statusnya masih Baru.</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="area_id">Area / Wilayah</label>
            <select name="area_id" id="area_id" required>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected($report->area_id == $area->id)>{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="description">Kondisi yang Ditemukan</label>
            <textarea name="description" id="description" rows="4" required>{{ old('description', $report->description) }}</textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="incident_at">Waktu Kejadian</label>
                <input type="datetime-local" name="incident_at" id="incident_at" value="{{ old('incident_at', $report->incident_at->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="latitude">Latitude (-90 s.d. 90)</label>
                <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $report->latitude) }}" required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude (-180 s.d. 180)</label>
                <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude) }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="evidence">Bukti Pendukung Baru (JPG/PNG/PDF, maksimal 2 MB, kosongkan jika tidak diganti)</label>
            <input type="file" name="evidence" id="evidence" accept=".jpg,.jpeg,.png,.pdf">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('reports.show', $report) }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
