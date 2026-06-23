<?php

namespace App\Models;

use App\Services\DashboardMetricsService;
use Illuminate\Database\Eloquent\Model;

class DailyTip extends Model
{
    protected $fillable = [
        'user_id',
        'tip_text',
        'status',
        'reviewed_by',
        'rejection_reason',
    ];

    /**
     * Obtiene el paciente (usuario) dueño del consejo.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene el médico o cuidador que revisó/moderó el consejo.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    protected static function booted(): void
    {
        static::saved(function (DailyTip $tip) {
            DashboardMetricsService::forgetUserCache($tip->user_id);
        });

        static::deleted(function (DailyTip $tip) {
            DashboardMetricsService::forgetUserCache($tip->user_id);
        });
    }
}
