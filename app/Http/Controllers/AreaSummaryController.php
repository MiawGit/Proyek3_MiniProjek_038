<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Enums\Role;
use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AreaSummaryController extends Controller
{
    public function show(Request $request, Area $area): JsonResponse
    {
        $isPelapor = $request->user()->hasRole(Role::Pelapor);

        $counts = Report::query()
            ->where('area_id', $area->id)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $summary = [];
        foreach (ReportStatus::cases() as $status) {
            $summary[$status->value] = (int) ($counts[$status->value] ?? 0);
        }

        $data = [
            'area' => $area->name,
            'summary' => $summary,
            'total' => array_sum($summary),
        ];

        // Privasi: pelapor hanya melihat jumlah, relawan/koordinator melihat daftar laporan.
        if (! $isPelapor) {
            $data['reports'] = Report::query()
                ->where('area_id', $area->id)
                ->with('user')
                ->latest()
                ->get(['id', 'user_id', 'incident_at', 'status'])
                ->map(fn (Report $report) => [
                    'id' => $report->id,
                    'incident_at' => $report->incident_at->format('d M Y H:i'),
                    'status' => $report->status->label(),
                    'reported_by' => $report->user->name,
                ]);
        }

        return response()->json($data);
    }
}
