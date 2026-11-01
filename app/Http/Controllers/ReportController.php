<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Enums\Role;
use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportController extends Controller
{
    private const RULES = [
        'area_id' => ['required', 'exists:areas,id'],
        'description' => ['required', 'string', 'min:10'],
        'incident_at' => ['required', 'date', 'before_or_equal:now'],
        'latitude' => ['required', 'numeric', 'between:-90,90'],
        'longitude' => ['required', 'numeric', 'between:-180,180'],
        'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
    ];

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Report::class);

        $reports = Report::query()
            ->when($request->user()->hasRole(Role::Pelapor), fn ($q) => $q->where('user_id', $request->user()->id))
            ->when($request->filled('q'), fn ($q) => $q->where('description', 'like', '%' . $request->input('q') . '%'))
            ->when($request->filled('area_id'), fn ($q) => $q->where('area_id', $request->input('area_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->with(['user', 'area'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', [
            'reports' => $reports,
            'isPelapor' => $request->user()->hasRole(Role::Pelapor),
            'areas' => Area::orderBy('name')->get(),
            'statuses' => ReportStatus::cases(),
            'filters' => $request->only(['q', 'area_id', 'status']),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Report::class);

        return view('reports.create', [
            'areas' => Area::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Report::class);

        $data = $request->validate(self::RULES);

        $report = Report::create([
            'user_id' => $request->user()->id,
            'area_id' => $data['area_id'],
            'description' => $data['description'],
            'incident_at' => $data['incident_at'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'evidence_path' => isset($data['evidence'])
                ? $data['evidence']->store('evidence', 'local')
                : null,
        ]);

        return redirect()->route('reports.show', $report)
            ->with('status', 'Laporan berhasil dibuat.');
    }

    public function show(Request $request, Report $report): View
    {
        Gate::authorize('view', $report);

        $allowed = ReportStatus::nextFor($request->user()->role);

        return view('reports.show', [
            'report' => $report->load(['user', 'area', 'forwarder']),
            'canUpdate' => $request->user()->can('update', $report),
            'isRelawanOrKoordinator' => $request->user()->hasRole(Role::Relawan)
                || $request->user()->hasRole(Role::Koordinator),
            'reviewNotes' => $report->reviewNotes()->with('user')->latest()->get(),
            'statusChanges' => $report->statusChanges()->with('user')->latest()->get(),
            'allowedTransitions' => $allowed[$report->status->value] ?? [],
        ]);
    }

    public function edit(Request $request, Report $report): View
    {
        Gate::authorize('update', $report);

        return view('reports.edit', [
            'report' => $report,
            'areas' => Area::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        Gate::authorize('update', $report);

        $data = $request->validate(self::RULES);

        if (isset($data['evidence'])) {
            if ($report->evidence_path) {
                Storage::disk('local')->delete($report->evidence_path);
            }
            $report->evidence_path = $data['evidence']->store('evidence', 'local');
        }

        $report->update([
            'area_id' => $data['area_id'],
            'description' => $data['description'],
            'incident_at' => $data['incident_at'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'evidence_path' => $report->evidence_path,
        ]);

        return redirect()->route('reports.show', $report)
            ->with('status', 'Laporan berhasil diperbarui.');
    }

    public function evidence(Report $report)
    {
        Gate::authorize('view', $report);

        abort_if(! $report->evidence_path, 404);

        return Storage::disk('local')->response($report->evidence_path);
    }
}
