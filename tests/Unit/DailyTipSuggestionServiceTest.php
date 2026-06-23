<?php

namespace Tests\Unit;

use App\Services\DailyTipSuggestionService;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DailyTipSuggestionServiceTest extends TestCase
{
    private DailyTipSuggestionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DailyTipSuggestionService();
    }

    #[DataProvider('extremeGlucoseCases')]
    public function test_returns_alert_for_extreme_glucose(array $metrics): void
    {
        $tip = $this->service->generate($metrics);

        $this->assertSame('Tus niveles de glucosa requieren atención. Por favor, sigue las indicaciones de tu médico y contáctalo de ser necesario.', $tip);
    }

    public function test_returns_local_free_tip_when_activity_is_low(): void
    {
        $tip = $this->service->generate([
            'glucose_average' => 118,
            'carbs_yesterday' => 85,
            'activity_minutes_yesterday' => 10,
            'days_since_last_tip' => 0,
        ]);

        $this->assertStringContainsString('caminar', mb_strtolower($tip));
    }

    public function test_returns_local_free_tip_when_no_glucose_data_exists(): void
    {
        $tip = $this->service->generate([
            'glucose_average' => null,
            'carbs_yesterday' => 40,
            'activity_minutes_yesterday' => 35,
            'days_since_last_tip' => 1,
        ]);

        $this->assertNotEmpty($tip);
        $this->assertLessThanOrEqual(140, strlen($tip));
    }

    public function test_anthropic_provider_returns_tip_from_api_response(): void
    {
        Http::fake([
            'api.anthropic.com/v1/messages' => Http::response([
                'content' => [
                    ['text' => 'Camina 10 minutos después de comer para apoyar tu glucosa.'],
                ],
            ], 200),
        ]);

        $tip = $this->service->generateAnthropic([
            'glucose_average' => 118,
            'carbs_yesterday' => 85,
            'activity_minutes_yesterday' => 30,
            'days_since_last_tip' => 0,
        ], 'test-key', 'claude-haiku-4-5');

        $this->assertSame('Camina 10 minutos después de comer para apoyar tu glucosa.', $tip);
    }

    public function test_anthropic_provider_skips_api_when_glucose_is_extreme(): void
    {
        Http::fake();

        $tip = $this->service->generateAnthropic([
            'glucose_average' => 300,
            'carbs_yesterday' => 85,
            'activity_minutes_yesterday' => 30,
            'days_since_last_tip' => 0,
        ], 'test-key', 'claude-haiku-4-5');

        Http::assertNothingSent();

        $this->assertSame('Tus niveles de glucosa requieren atención. Por favor, sigue las indicaciones de tu médico y contáctalo de ser necesario.', $tip);
    }

    public function test_anthropic_provider_throws_when_api_key_is_missing(): void
    {
        $this->expectException(RuntimeException::class);

        $this->service->generateAnthropic([
            'glucose_average' => 118,
            'carbs_yesterday' => 85,
            'activity_minutes_yesterday' => 30,
            'days_since_last_tip' => 0,
        ], null, 'claude-haiku-4-5');
    }

    public static function extremeGlucoseCases(): array
    {
        return [
            'low' => [[
                'glucose_average' => 60,
                'carbs_yesterday' => 50,
                'activity_minutes_yesterday' => 20,
                'days_since_last_tip' => 0,
            ]],
            'high' => [[
                'glucose_average' => 280,
                'carbs_yesterday' => 50,
                'activity_minutes_yesterday' => 20,
                'days_since_last_tip' => 0,
            ]],
        ];
    }
}
