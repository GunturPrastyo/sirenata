<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'duration',
        'team_leader',
        'team_members',
        'type',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'team_members' => 'array',
    ];

    public function leader()
    {
        return $this->belongsTo(\App\Models\User::class, 'team_leader');
    }

    public function getProgressAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $now = now();

        if ($now->lessThan($this->start_date)) {
            return 0;
        }

        if ($now->greaterThan($this->end_date)) {
            return 100;
        }

        $totalDuration = $this->start_date->diffInDays($this->end_date);
        $elapsedDuration = $this->start_date->diffInDays($now);

        if ($totalDuration <= 0) {
            return 100;
        }

        return round(($elapsedDuration / $totalDuration) * 100);
    }
}
