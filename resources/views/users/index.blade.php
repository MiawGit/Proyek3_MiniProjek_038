@extends('layouts.app')

@section('title', 'Kelola Akun Relawan | Siaga Karhutla')

@section('content')
<div class="page-head">
    <h1>Kelola Akun</h1>
    <p class="muted">Daftar akun pelapor dan relawan pemantau.</p>
</div>

<div class="card">
    <h3>Tambah Akun Relawan</h3>
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group grow">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group grow">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" id="password" minlength="8" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Ulangi Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" required>
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
                <th>Email</th>
                <th>Peran</th>
                <th>Jumlah Laporan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge badge-role">{{ $user->role->label() }}</span></td>
                    <td>{{ $user->reports_count }}</td>
                    <td class="text-right">
                        @if ($user->role->value === 'relawan')
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline-form" onsubmit="return confirm('Hapus akun relawan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
