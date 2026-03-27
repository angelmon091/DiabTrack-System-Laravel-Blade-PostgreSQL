<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'intensity',
        'activity_type',
        'energy_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
