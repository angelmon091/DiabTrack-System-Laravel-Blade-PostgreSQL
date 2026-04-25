<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo VitalSign
 * 
 * Representa los registros de signos vitales de un usuario, incluyendo niveles 
 * de glucosa, presión arterial y hemoglobina glicosilada (HbA1c).
 */
class VitalSign extends Model
{
    use HasFactory;

    /**
     * Atributos asignables de forma masiva.
     * 
     * - glucose_level: Nivel de azúcar en sangre (mg/dL).
     * - systolic/diastolic: Presión arterial.
     * - hba1c: Promedio de glucosa de los últimos 3 meses (%).
     * - measurement_moment: Momento de la toma (ayunas, después de comer, etc).
     */
    protected $fillable = [
        'user_id',
        'glucose_level',
        'systolic',
        'diastolic',
        'heart_rate',
        'weight',
        'hba1c',
        'measurement_moment',
        'stress_level',
        'notes',
    ];

    /**
     * Obtiene el usuario al que pertenece el registro.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar registros por un usuario específico.
     */
    public function scopeDelUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para obtener solo los registros realizados el día de hoy.
     */
    public function scopeDeHoy($query)
    {
        return $query->whereDate('created_at', \Carbon\Carbon::today());
    }
}
