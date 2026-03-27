<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionLog extends Model
{
    protected $fillable = [
        'user_id',
        'meal_type',
        'carbs_grams',
        'consumed_at',
        'food_categories',
        'medication_taken',
        'medication_dose',
    ];

    protected $casts = [
        'food_categories' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
