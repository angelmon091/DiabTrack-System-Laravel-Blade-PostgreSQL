<?php

namespace Tests\Feature;

use App\Models\DailyTip;
use App\Models\PatientLink;
use App\Models\Role;
use App\Models\User;
use App\Services\DashboardMetricsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DailyTipApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_daily_tip_invalidates_dashboard_cache(): void
    {
        $patient = $this->createUserWithRole('paciente');
        $cacheKey = DashboardMetricsService::cacheKey($patient->id);

        Cache::put($cacheKey, ['tipDelDia' => 'Tip en caché desactualizado'], 300);

        DailyTip::create([
            'user_id' => $patient->id,
            'tip_text' => 'Camina 10 minutos después de comer.',
            'status' => 'approved',
        ]);

        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_linked_caregiver_can_approve_a_pending_tip(): void
    {
        $patient = $this->createUserWithRole('paciente');
        $reviewer = $this->createUserWithRole('cuidador');

        PatientLink::create([
            'patient_id' => $patient->id,
            'linked_user_id' => $reviewer->id,
            'role' => 'cuidador',
            'invite_code' => 'ABC123',
            'status' => 'active',
            'expires_at' => now()->addDay(),
        ]);

        // Ahora los consejos se crean auto-aprobados
        $tip = DailyTip::create([
            'user_id' => $patient->id,
            'tip_text' => 'Camina 10 minutos después de comer.',
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('daily_tips', [
            'id' => $tip->id,
            'status' => 'approved',
        ]);
    }

    public function test_linked_caregiver_can_reject_a_tip_with_reason(): void
    {
        $patient = $this->createUserWithRole('paciente');
        $reviewer = $this->createUserWithRole('médico');

        PatientLink::create([
            'patient_id' => $patient->id,
            'linked_user_id' => $reviewer->id,
            'role' => 'médico',
            'invite_code' => 'DEF456',
            'status' => 'active',
            'expires_at' => now()->addDay(),
        ]);

        // Los consejos se crean aprobados por defecto, pero el cuidador puede rechazarlos
        $tip = DailyTip::create([
            'user_id' => $patient->id,
            'tip_text' => 'Aumenta la insulina antes de la cena.',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($reviewer)
            ->post(route('tips.reject', $tip), [
                'reason' => 'El consejo propone un cambio terapéutico no permitido.',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('daily_tips', [
            'id' => $tip->id,
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'rejection_reason' => 'El consejo propone un cambio terapéutico no permitido.',
        ]);
    }

    private function createUserWithRole(string $roleName): User
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(
            ['name' => $roleName],
            ['description' => ucfirst($roleName)]
        );

        $user->roles()->attach($role->id);

        return $user;
    }
}
