<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'duration_minutes',
        'intensity',
        'start_time',
        'end_time',
        'energy_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
