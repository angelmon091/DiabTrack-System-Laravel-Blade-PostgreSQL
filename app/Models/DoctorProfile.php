<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = ['user_id', 'gender', 'license_number', 'specialty'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
