<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DailyTipSuggestionService
{
    private const ALERT_MESSAGE = 'Tus niveles de glucosa requieren atención. Por favor, sigue las indicaciones de tu médico y contáctalo de ser necesario.';

    public function generate(array $metrics): string
    {
        if ($alertMessage = $this->extremeGlucoseAlert($metrics)) {
            return $alertMessage;
        }

        $carbsYesterday = (int) ($metrics['carbs_yesterday'] ?? 0);
        $activityMinutesYesterday = (int) ($metrics['activity_minutes_yesterday'] ?? 0);
        $daysSinceLastTip = (int) ($metrics['days_since_last_tip'] ?? 0);

        if (($metrics['glucose_average'] ?? null) === null) {
            return $this->pick([
                'Mantente hidratado y registra tu glucosa para detectar patrones a tiempo.',
                'Una caminata ligera y breve puede ayudarte a mejorar tu sensibilidad a la insulina.',
                'Ordena tu plato: primero fibra, luego carbohidratos, para suavizar los picos de glucosa.',
                'Dormir bien y reducir el estrés también ayuda a controlar la glucosa.',
            ], $carbsYesterday + $activityMinutesYesterday + $daysSinceLastTip);
        }

        if ($activityMinutesYesterday < 20) {
            return 'Intenta caminar 10 a 15 minutos después de comer para ayudar a tu glucosa.';
        }

        if ($carbsYesterday > 120) {
            return 'Prioriza fibra y proteína antes de los carbohidratos para reducir picos de glucosa.';
        }

        if ($daysSinceLastTip > 0) {
            return 'Mantén tu rutina de agua, sueño y movimiento para apoyar un control más estable.';
        }

        return $this->pick([
            'Beber agua durante el día ayuda a mantener un mejor equilibrio general.',
            'Un paseo corto después de comer puede apoyar tu control glucémico.',
            'Dormir bien esta noche puede mejorar tu energía y el control de glucosa mañana.',
            'Reducir el estrés también forma parte del cuidado diario de tu diabetes.',
        ], $carbsYesterday + $activityMinutesYesterday);
    }

    public function generateAnthropic(array $metrics, ?string $apiKey = null, ?string $modelName = null): string
    {
        if ($alertMessage = $this->extremeGlucoseAlert($metrics)) {
            return $alertMessage;
        }

        if (empty($apiKey)) {
            throw new \RuntimeException('ANTHROPIC_API_KEY no configurada.');
        }

        $model = $modelName ?: config('services.anthropic.model', 'claude-haiku-4-5');

        try {
            $response = Http::timeout(25)
                ->acceptJson()
                ->withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => config('services.anthropic.version', '2023-06-01'),
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => $model,
                    'max_tokens' => 120,
                    'temperature' => 0.4,
                    'system' => $this->anthropicSystemPrompt(),
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $this->anthropicUserPrompt($metrics),
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                throw new \RuntimeException('Anthropic respondió con estado HTTP ' . $response->status());
            }

            $tip = trim(collect($response->json('content', []))->pluck('text')->filter()->implode(' '));

            if ($tip === '') {
                throw new \RuntimeException('Anthropic devolvió una respuesta vacía.');
            }

            return $tip;
        } catch (\Throwable $e) {
            Log::warning('GenerateDailyTips: error de Anthropic sin fallback local: ' . $e->getMessage());

            throw new \RuntimeException('No se pudo generar el tip con Anthropic.', 0, $e);
        }
    }

    private function extremeGlucoseAlert(array $metrics): ?string
    {
        $glucoseAverage = $metrics['glucose_average'] ?? null;

        if ($glucoseAverage !== null && ($glucoseAverage < 70 || $glucoseAverage > 250)) {
            return self::ALERT_MESSAGE;
        }

        return null;
    }

    private function anthropicSystemPrompt(): string
    {
        return 'Eres un asistente virtual de apoyo y bienestar diario para personas con diabetes mellitus. '
            . 'Debes devolver un consejo breve, útil y no diagnóstico. '
            . 'NUNCA sugieras cambios de medicamentos, insulina, dosis o tratamientos farmacológicos. '
            . 'Tus consejos solo pueden tratar hidratación, sueño, caminatas ligeras, orden de comidas, fibra y reducción de estrés. '
            . 'Mantén un tono empático, claro y breve. '
            . 'Si el promedio de glucosa está fuera de rango extremo, devuelve exactamente el mensaje de alerta indicado por el sistema.';
    }

    private function anthropicUserPrompt(array $metrics): string
    {
        $glucoseAverage = $metrics['glucose_average'] ?? null;

        return "Datos del paciente:\n"
            . '- Promedio glucosa 48h: ' . ($glucoseAverage !== null ? round((float) $glucoseAverage, 1) . ' mg/dL' : 'Sin registros') . "\n"
            . '- Carbohidratos consumidos ayer: ' . (int) ($metrics['carbs_yesterday'] ?? 0) . " gramos\n"
            . '- Minutos de actividad física ayer: ' . (int) ($metrics['activity_minutes_yesterday'] ?? 0) . " minutos\n"
            . '- Días desde el último consejo: ' . (int) ($metrics['days_since_last_tip'] ?? 0) . "\n"
            . 'Genera un consejo de no más de 140 caracteres.';
    }

    private function pick(array $tips, int $seed): string
    {
        return $tips[$seed % count($tips)];
    }
}
