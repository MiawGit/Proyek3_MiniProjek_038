<?php

namespace App\Policies;

use App\Enums\ReportStatus;
use App\Enums\Role;
use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Report $report): bool
    {
        if ($user->hasRole(Role::Pelapor)) {
            return $report->user_id === $user->id;
        }

        return $user->hasRole(Role::Relawan) || $user->hasRole(Role::Koordinator);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::Pelapor);
    }

    public function update(User $user, Report $report): bool
    {
        return $user->hasRole(Role::Pelapor)
            && $report->user_id === $user->id
            && $report->status === ReportStatus::Baru;
    }
}
