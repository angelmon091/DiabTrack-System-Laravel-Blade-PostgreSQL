<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientLink extends Model
{
    protected $fillable = ['patient_id', 'linked_user_id', 'role', 'invite_code', 'status', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function linkedUser()
    {
        return $this->belongsTo(User::class, 'linked_user_id');
    }
}
