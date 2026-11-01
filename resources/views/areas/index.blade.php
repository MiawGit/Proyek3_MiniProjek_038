@extends('layouts.app')

@section('title', 'Kelola Area | Siaga Karhutla')

@section('content')
<div class="page-head">
    <h1>Kelola Area</h1>
    <p class="muted">Daftar area rawan kebakaran hutan dan lahan.</p>
</div>

<div class="card">
    <h3>Tambah Area Baru</h3>
    <form method="POST" action="{{ route('areas.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group grow">
                <label for="name">Nama Area</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="mis. Perbukitan Cibiru" required>
            </div>
            <div class="form-group grow">
                <label for="description">Deskripsi (opsional)</label>
                <input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Kondisi vegetasi, tingkat risiko, dsb.">
            </div>
            <div class="form-group self-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
</div>

<div class="card table-card">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Jumlah Laporan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($areas as $area)
                <tr>
                    <td>{{ $area->id }}</td>
                    <td>{{ $area->name }}</td>
                    <td class="muted">{{ $area->description ?? 'Tidak ada deskripsi' }}</td>
                    <td>{{ $area->reports_count }}</td>
                    <td class="text-right">
                        <a href="{{ route('areas.edit', $area) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form method="POST" action="{{ route('areas.destroy', $area) }}" class="inline-form" onsubmit="return confirm('Hapus area ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
