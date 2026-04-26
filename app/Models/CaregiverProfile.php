<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaregiverProfile extends Model
{
    protected $fillable = ['user_id', 'gender', 'relationship'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
