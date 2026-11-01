<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(): View
    {
        return view('areas.index', [
            'areas' => Area::withCount('reports')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:areas,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Area::create($data);

        return redirect()->route('areas.index')->with('status', 'Area berhasil ditambahkan.');
    }

    public function edit(Area $area): View
    {
        return view('areas.edit', ['area' => $area]);
    }

    public function update(Request $request, Area $area): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:areas,name,' . $area->id],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $area->update($data);

        return redirect()->route('areas.index')->with('status', 'Area berhasil diperbarui.');
    }

    public function destroy(Area $area): RedirectResponse
    {
        if ($area->reports()->exists()) {
            return back()->withErrors(['area' => 'Area tidak dapat dihapus karena masih memiliki laporan.']);
        }

        $area->delete();

        return redirect()->route('areas.index')->with('status', 'Area berhasil dihapus.');
    }
}
