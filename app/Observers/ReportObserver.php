<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\StatusChange;
use Illuminate\Support\Facades\Auth;

class ReportObserver
{
    public function updating(Report $report): void
    {
        if (! $report->isDirty('status')) {
            return;
        }

        StatusChange::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'from_status' => $report->getOriginal('status'),
            'to_status' => $report->status->value,
        ]);
    }
}
