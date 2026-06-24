<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DailyTipSuggestionService
{
    private const ALERT_HYPOGLYCEMIA = 'Tu glucosa ha estado muy baja. Sigue las indicaciones de tu médico y ten a mano un carbohidrato de absorción rápida.';
    private const ALERT_HYPERGLYCEMIA = 'Tu glucosa ha estado muy elevada. Por favor, sigue las indicaciones de tu médico y contáctalo de ser necesario.';

    // ─────────────────────────────────────────────────────────────────────────
    // Punto de entrada principal
    // ─────────────────────────────────────────────────────────────────────────

    public function generateAnthropic(array $context, ?string $apiKey = null, ?string $modelName = null): string
    {
        // Seguridad: alerta inmediata si glucosa extrema
        if ($alert = $this->extremeGlucoseAlert($context)) {
            return $alert;
        }

        if (empty($apiKey)) {
            throw new \RuntimeException('ANTHROPIC_API_KEY no configurada.');
        }

        $model = $modelName ?: config('services.anthropic.model', 'claude-haiku-4-5');

        try {
            $response = Http::timeout(30)
                ->acceptJson()
                ->withHeaders([
                    'x-api-key'         => $apiKey,
                    'anthropic-version' => config('services.anthropic.version', '2023-06-01'),
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'       => $model,
                    'max_tokens'  => 180,
                    'temperature' => 0.5,
                    'system'      => $this->systemPrompt(),
                    'messages'    => [
                        [
                            'role'    => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $this->buildUserPrompt($context),
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                throw new \RuntimeException('Anthropic respondió con estado HTTP ' . $response->status());
            }

            $tip = trim(
                collect($response->json('content', []))->pluck('text')->filter()->implode(' ')
            );

            if ($tip === '') {
                throw new \RuntimeException('Anthropic devolvió una respuesta vacía.');
            }

            return $tip;

        } catch (\Throwable $e) {
            Log::warning('GenerateDailyTips: error de Anthropic — ' . $e->getMessage());
            throw new \RuntimeException('No se pudo generar el tip con Anthropic.', 0, $e);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // System Prompt — define el rol y los límites de la IA
    // ─────────────────────────────────────────────────────────────────────────

    private function systemPrompt(): string
    {
        return <<<PROMPT
Eres un asistente virtual de bienestar y apoyo diario especializado en diabetes mellitus.
Tu función es generar UN ÚNICO consejo diario, breve, empático y accionable para el paciente,
basándote en sus datos clínicos y de estilo de vida del día anterior.

REGLAS ABSOLUTAS:
1. NUNCA sugieras cambios de medicamentos, insulina, dosis ni tratamientos farmacológicos.
2. NUNCA hagas diagnósticos médicos.
3. Tus consejos deben centrarse en: hidratación, sueño, caminatas, orden de comidas, fibra,
   reducción de estrés, hábitos de medición y autocuidado general.
4. Si el paciente reportó síntomas preocupantes (hipoglucemia, mareos, visión borrosa), prioriza
   ese contexto en el consejo (sin indicar medicación).
5. Personaliza el consejo según el tipo de diabetes, edad, nivel de estrés, actividad y nutrición.
6. Responde SOLO el texto del consejo, sin saludos, sin títulos, sin explicaciones adicionales.
7. Máximo 160 caracteres. Tono cálido, claro y motivador.
PROMPT;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // User Prompt — inyecta todos los datos disponibles del paciente
    // ─────────────────────────────────────────────────────────────────────────

    private function buildUserPrompt(array $c): string
    {
        $lines = [];

        // ── Perfil clínico ──────────────────────────────────────────────────
        $lines[] = '=== PERFIL CLÍNICO ===';
        $lines[] = '- Tipo de diabetes: '     . ($c['tipo_diabetes']     ?? 'No especificado');
        $lines[] = '- Edad: '                 . ($c['edad']              ? $c['edad'] . ' años'    : 'No especificada');
        $lines[] = '- Género: '               . ($c['genero']            ?? 'No especificado');
        $lines[] = '- IMC calculado: '        . ($c['imc']               ? $c['imc']               : 'Sin datos de peso/talla');
        $lines[] = '- Peso: '                 . ($c['peso_kg']           ? $c['peso_kg'] . ' kg'   : 'No registrado');
        $lines[] = '- Rango glucosa objetivo: '. ($c['rango_glucosa_min'] ?? 70) . '–' . ($c['rango_glucosa_max'] ?? 140) . ' mg/dL';

        // ── Glucosa ─────────────────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== GLUCOSA (últimas 48h) ===';
        $lines[] = '- Promedio: '    . ($c['glucosa_promedio_48h'] !== null ? $c['glucosa_promedio_48h'] . ' mg/dL' : 'Sin registros');
        $lines[] = '- Mínima: '     . ($c['glucosa_min_48h']       !== null ? $c['glucosa_min_48h']      . ' mg/dL' : 'Sin registros');
        $lines[] = '- Máxima: '     . ($c['glucosa_max_48h']       !== null ? $c['glucosa_max_48h']      . ' mg/dL' : 'Sin registros');
        $lines[] = '- Momento habitual de medición: ' . ($c['momento_medicion'] ?? 'No especificado');
        if ($c['hba1c'] ?? null) {
            $lines[] = '- HbA1c más reciente: ' . $c['hba1c'] . '%';
        }

        // ── Otros signos vitales ────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== OTROS SIGNOS VITALES ===';
        if (($c['presion_sistolica'] ?? null) && ($c['presion_diastolica'] ?? null)) {
            $lines[] = '- Presión arterial: ' . $c['presion_sistolica'] . '/' . $c['presion_diastolica'] . ' mmHg';
        } else {
            $lines[] = '- Presión arterial: Sin registro reciente';
        }
        $lines[] = '- Frecuencia cardíaca: '  . ($c['frecuencia_cardiaca'] ? $c['frecuencia_cardiaca'] . ' bpm' : 'Sin registro');
        $lines[] = '- Nivel de estrés: '      . ($c['nivel_estres']        ? $c['nivel_estres'] . '/10'         : 'Sin registro');
        if ($c['nota_vital'] ?? null) {
            $lines[] = '- Nota del paciente en último registro: "' . $c['nota_vital'] . '"';
        }

        // ── Actividad física ────────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== ACTIVIDAD FÍSICA (ayer) ===';
        $lines[] = '- Minutos de actividad: '   . ($c['minutos_actividad_ayer'] > 0 ? $c['minutos_actividad_ayer'] . ' min' : 'Ninguna');
        $lines[] = '- Tipos de ejercicio: '      . (count($c['tipos_actividad_ayer'] ?? []) > 0 ? implode(', ', $c['tipos_actividad_ayer']) : 'Ninguno');
        $lines[] = '- Intensidad: '              . (count($c['intensidad_actividad'] ?? []) > 0 ? implode(', ', $c['intensidad_actividad'])   : 'No especificada');
        $lines[] = '- Energía post-actividad: '  . ($c['energia_post_actividad'] ?? 'No registrada');

        // ── Nutrición ───────────────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== NUTRICIÓN (ayer) ===';
        $lines[] = '- Carbohidratos consumidos: ' . ($c['carbs_ayer_gramos'] > 0 ? $c['carbs_ayer_gramos'] . ' g' : 'Sin registros');
        $lines[] = '- Comidas registradas: '       . (count($c['tipos_comida_ayer'] ?? []) > 0 ? implode(', ', $c['tipos_comida_ayer'])        : 'Ninguna');
        $lines[] = '- Grupos de alimentos: '       . (count($c['categorias_alimentos'] ?? []) > 0 ? implode(', ', $c['categorias_alimentos']) : 'No especificados');

        // ── Síntomas ────────────────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== SÍNTOMAS RECIENTES (48h) ===';
        $lines[] = count($c['sintomas_recientes'] ?? []) > 0
            ? '- Síntomas reportados: ' . implode(', ', $c['sintomas_recientes'])
            : '- Sin síntomas reportados';

        // ── Instrucción final ───────────────────────────────────────────────
        $lines[] = '';
        $lines[] = 'Con base en todos estos datos, genera UN consejo diario personalizado, empático y accionable de máximo 160 caracteres.';
        $lines[] = 'Prioriza el dato más relevante o preocupante del paciente en este momento.';

        return implode("\n", $lines);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Alerta de glucosa extrema (seguridad del paciente)
    // ─────────────────────────────────────────────────────────────────────────

    private function extremeGlucoseAlert(array $context): ?string
    {
        $glucosa = $context['glucosa_promedio_48h'] ?? $context['glucose_average'] ?? null;

        if ($glucosa === null) {
            return null;
        }

        if ($glucosa < 70) {
            return self::ALERT_HYPOGLYCEMIA;
        }

        if ($glucosa > 250) {
            return self::ALERT_HYPERGLYCEMIA;
        }

        return null;
    }
}
