<?php

namespace App\Http\Controllers;

use App\Models\DailyTip;
use App\Models\PatientLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyTipController extends Controller
{
    /**
     * Aprueba el tip diario sugerido.
     */
    public function approve(DailyTip $tip)
    {
        $this->authorizeAccess($tip);

        $tip->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
        ]);

        return back()->with('status', 'Consejo aprobado y visible para el paciente.');
    }

    /**
     * Rechaza el tip diario sugerido con un motivo.
     */
    public function reject(Request $request, DailyTip $tip)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $this->authorizeAccess($tip);

        $tip->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('status', 'Consejo rechazado correctamente.');
    }

    /**
     * Valida que el usuario tenga un vínculo activo con el paciente.
     */
    protected function authorizeAccess(DailyTip $tip)
    {
        $user = Auth::user();

        if (!$user || (!$user->isDoctor() && !$user->isCaregiver())) {
            abort(403, 'No tienes permiso para gestionar los consejos de este paciente.');
        }

        $isLinked = PatientLink::where('patient_id', $tip->user_id)
            ->where('linked_user_id', $user->id)
            ->where('status', 'active')
            ->exists();

        if (!$isLinked) {
            abort(403, 'No tienes permiso para gestionar los consejos de este paciente.');
        }
    }
}
