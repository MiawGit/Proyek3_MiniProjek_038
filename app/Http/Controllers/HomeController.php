<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\Report;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Report::count(),
            'baru' => Report::where('status', ReportStatus::Baru)->count(),
            'selesai' => Report::where('status', ReportStatus::Selesai)->count(),
            'perlu' => Report::where('status', ReportStatus::PerluTindakLanjut)->count(),
        ];

        $reports = Report::with('area')
            ->latest('incident_at')
            ->take(6)
            ->get(['id', 'area_id', 'description', 'incident_at', 'status']);

        return view('landing', compact('stats', 'reports'));
    }
}
