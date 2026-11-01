@extends('layouts.app')

@section('title', 'Buat Laporan | Siaga Karhutla')

@section('content')
<div class="page-head">
    <h1>Buat Laporan Baru</h1>
    <p class="muted">Laporkan indikasi kebakaran hutan dan lahan yang Anda lihat.</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="area_id">Area / Wilayah</label>
            <select name="area_id" id="area_id" required>
                <option value="">Pilih area</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected(old('area_id') == $area->id)>{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="description">Kondisi yang Ditemukan</label>
            <textarea name="description" id="description" rows="4" placeholder="Contoh: terlihat asap tebal berwarna hitam dari arah timur perkebunan..." required>{{ old('description') }}</textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="incident_at">Waktu Kejadian</label>
                <input type="datetime-local" name="incident_at" id="incident_at" value="{{ old('incident_at') }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="latitude">Latitude (-90 s.d. 90)</label>
                <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude (-180 s.d. 180)</label>
                <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="evidence">Bukti Pendukung (JPG/PNG/PDF, maksimal 2 MB)</label>
            <input type="file" name="evidence" id="evidence" accept=".jpg,.jpeg,.png,.pdf">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Kirim Laporan</button>
            <a href="{{ route('reports.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
