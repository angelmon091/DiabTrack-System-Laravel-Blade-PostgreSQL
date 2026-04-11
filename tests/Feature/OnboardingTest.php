<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_onboarding_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/onboarding');

        $response->assertStatus(200);
    }

    public function test_user_can_submit_personal_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/onboarding', [
            'birth_day' => '15',
            'birth_month' => 'Marzo',
            'birth_year' => '1990',
            'diabetes_type' => 'Diabetes Mellitus Tipo 2',
            'weight' => '80.5',
            'height' => '175',
            'gender' => 'Masculino',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('patient_profiles', [
            'user_id' => $user->id,
            'birth_date' => '1990-03-15',
            'weight' => 80.5,
        ]);
    }
}
