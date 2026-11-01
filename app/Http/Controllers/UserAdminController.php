<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAdminController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'users' => User::withCount('reports')
                ->whereIn('role', [Role::Relawan->value, Role::Pelapor->value])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => Role::Relawan,
        ]);

        return redirect()->route('users.index')->with('status', 'Akun relawan berhasil dibuat.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(! $user->hasRole(Role::Relawan), 403, 'Hanya akun relawan yang dapat dihapus.');

        $user->delete();

        return redirect()->route('users.index')->with('status', 'Akun relawan berhasil dihapus.');
    }
}
