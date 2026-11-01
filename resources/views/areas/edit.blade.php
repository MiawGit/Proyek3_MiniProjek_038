@extends('layouts.app')

@section('title', 'Edit Area | Siaga Karhutla')

@section('content')
<div class="page-head">
    <h1>Edit Area</h1>
    <p class="muted">Perbarui data area #{{ $area->id }}.</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('areas.update', $area) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama Area</label>
            <input type="text" name="name" id="name" value="{{ old('name', $area->name) }}" required>
        </div>
        <div class="form-group">
            <label for="description">Deskripsi (opsional)</label>
            <input type="text" name="description" id="description" value="{{ old('description', $area->description) }}">
        </div>
        <div class="action-bar">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('areas.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
