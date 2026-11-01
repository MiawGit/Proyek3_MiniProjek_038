<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ReportProgressController extends Controller
{
    public function storeNote(Request $request, Report $report): RedirectResponse
    {
        Gate::authorize('view', $report);

        $data = $request->validate([
            'content' => ['required', 'string', 'min:5'],
        ]);

        $report->reviewNotes()->create([
            'user_id' => $request->user()->id,
            'content' => $data['content'],
        ]);

        return redirect()->route('reports.show', $report)
            ->with('status', 'Catatan berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        Gate::authorize('view', $report);

        $user = $request->user();
        $allowed = ReportStatus::nextFor($user->role);

        // BR-02: status akhir (Selesai) dan transisi tanpa jalur berikutnya
        // tidak boleh diubah lagi.
        if (! array_key_exists($report->status->value, $allowed)) {
            abort(403, 'Status laporan saat ini tidak dapat diubah oleh peran Anda.');
        }

        $targets = $allowed[$report->status->value];

        $data = $request->validate([
            'status' => ['required', Rule::in(array_map(fn ($s) => $s->value, $targets))],
            'forwarded_to' => ['required_if:status,diteruskan', 'nullable', 'string', 'max:255'],
        ]);

        $to = ReportStatus::from($data['status']);
        $report->status = $to;

        if ($to === ReportStatus::Diteruskan) {
            $report->forwarded_to = $data['forwarded_to'];
            $report->forwarded_at = now();
            $report->forwarded_by = $user->id;
        }

        $report->save();

        return redirect()->route('reports.show', $report)
            ->with('status', 'Status laporan diperbarui menjadi "' . $to->label() . '".');
    }
}
