<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekapController extends Controller
{
    public function index(Request $request): View
    {
        $areaId = $request->input('area_id');

        $base = Report::query()->when($areaId, fn ($q) => $q->where('area_id', $areaId));

        $total = (clone $base)->count();
        $selesai = (clone $base)->where('status', ReportStatus::Selesai->value)->count();
        $perlu = (clone $base)->where('status', ReportStatus::PerluTindakLanjut->value)->count();
        $diteruskan = (clone $base)->where('status', ReportStatus::Diteruskan->value)->count();

        $countsByArea = [];
        foreach ((clone $base)->get(['area_id', 'status']) as $report) {
            $key = (string) $report->area_id;
            $countsByArea[$key][$report->status->value] = ($countsByArea[$key][$report->status->value] ?? 0) + 1;
        }

        $rows = [];
        foreach (Area::orderBy('name')->when($areaId, fn ($q) => $q->where('id', $areaId))->get() as $area) {
            $row = ['name' => $area->name, 'counts' => [], 'total' => 0];
            foreach (ReportStatus::cases() as $status) {
                $row['counts'][$status->value] = $countsByArea[(string) $area->id][$status->value] ?? 0;
                $row['total'] += $row['counts'][$status->value];
            }
            $rows[] = $row;
        }

        return view('rekap', [
            'areas' => Area::orderBy('name')->get(),
            'statuses' => ReportStatus::cases(),
            'selectedArea' => $areaId,
            'cards' => [
                'total' => $total,
                'selesai' => $selesai,
                'perlu' => $perlu,
                'diteruskan' => $diteruskan,
            ],
            'rows' => $rows,
        ]);
    }
}
