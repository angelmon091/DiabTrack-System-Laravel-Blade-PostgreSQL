<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Symptom extends Model
{
    protected $fillable = ['name', 'category'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'symptom_user')->withPivot('logged_at')->withTimestamps();
    }
}
