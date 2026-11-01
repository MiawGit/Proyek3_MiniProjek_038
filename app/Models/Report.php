<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'area_id',
        'description',
        'incident_at',
        'latitude',
        'longitude',
        'status',
        'evidence_path',
        'forwarded_to',
        'forwarded_at',
        'forwarded_by',
    ];

    protected function casts(): array
    {
        return [
            'incident_at' => 'datetime',
            'forwarded_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
            'status' => ReportStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function forwarder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    public function reviewNotes(): HasMany
    {
        return $this->hasMany(ReviewNote::class);
    }

    public function statusChanges(): HasMany
    {
        return $this->hasMany(StatusChange::class);
    }
}
