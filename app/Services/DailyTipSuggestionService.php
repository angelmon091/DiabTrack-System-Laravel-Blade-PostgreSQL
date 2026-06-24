<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DailyTipSuggestionService
{
    // Las alertas clínicas se manejan dentro del prompt de la IA para mantener tono cálido y personalización

    // ─────────────────────────────────────────────────────────────────────────
    // Punto de entrada principal
    // ─────────────────────────────────────────────────────────────────────────

    public function generateAnthropic(array $context, ?string $apiKey = null, ?string $modelName = null): string
    {

        if (empty($apiKey)) {
            throw new \RuntimeException('ANTHROPIC_API_KEY no configurada.');
        }

        $model = $modelName ?: config('services.anthropic.model', 'claude-haiku-4-5');

        try {
            $response = Http::timeout(30)
                ->acceptJson()
                ->withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => config('services.anthropic.version', '2023-06-01'),
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => $model,
                    'max_tokens' => 300,
                    'temperature' => 0.65,
                    'system' => $this->systemPrompt(),
                    'messages' => [
                        [
                            'role' => 'user',
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

    public function generateGemini(array $context, ?string $apiKey = null, ?string $modelName = null): string
    {

        $key = $apiKey ?: config('services.gemini.key');
        if (empty($key)) {
            throw new \RuntimeException('GEMINI_API_KEY no configurada.');
        }

        $model = $modelName ?: config('services.gemini.model', 'gemini-2.5-flash');

        try {
            $response = Http::timeout(30)
                ->acceptJson()
                ->post('https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . urlencode($key), [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $this->buildUserPrompt($context),
                                ],
                            ],
                        ],
                    ],
                    'systemInstruction' => [
                        'parts' => [
                            [
                                'text' => $this->systemPrompt(),
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 1000,
                        'temperature' => 0.65,
                    ],
                ]);

            if ($response->failed()) {
                throw new \RuntimeException('Gemini respondió con estado HTTP ' . $response->status());
            }

            $tip = trim((string) data_get($response->json(), 'candidates.0.content.parts.0.text', ''));

            if ($tip === '') {
                throw new \RuntimeException('Gemini devolvió una respuesta vacía.');
            }

            return $tip;

        } catch (\Throwable $e) {
            Log::warning('GenerateDailyTips: error de Gemini — ' . $e->getMessage());
            throw new \RuntimeException('No se pudo generar el tip con Gemini.', 0, $e);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // System Prompt — define el rol y los límites de la IA
    // ─────────────────────────────────────────────────────────────────────────

    private function systemPrompt(): string
    {
        return <<<PROMPT
Eres un asistente de bienestar especializado en diabetes mellitus. Tu tarea es generar UN ÚNICO
mensaje diario, breve, cálido y accionable para el paciente, basándote en sus datos del día anterior.

═══════════════════════════════
REGLAS ABSOLUTAS (sin excepciones)
═══════════════════════════════
1. NUNCA menciones medicamentos, insulina, dosis ni tratamientos farmacológicos.
2. NUNCA hagas diagnósticos. NUNCA uses lenguaje alarmista ni catastrófico.
3. Responde SOLO el texto del mensaje: sin saludos, títulos ni explicaciones adicionales.
4. Responde siempre en español, con tono cálido, claro y motivador.
5. Máximo 220 caracteres. Sé conciso pero útil.

═══════════════════════════════
MANEJO DE VALORES FUERA DE RANGO
═══════════════════════════════
Si algún valor clínico está fuera del rango normal, menciónalo con calma y recomienda
contactar al médico pronto, sin urgencias ni alarma. Ejemplo de tono correcto:
"Tu glucosa estuvo un poco elevada ayer. Vale la pena comentárselo a tu médico en tu próxima visita."
NUNCA uses palabras como "urgente", "peligroso", "crisis" o "inmediatamente".

═══════════════════════════════
CÓMO PERSONALIZAR EL CONSEJO
═══════════════════════════════
Antes de responder, analiza TODOS los datos disponibles y elige el aspecto más relevante HOY.
Rota entre estos temas según lo que los datos del paciente indiquen — nunca repitas el último consejo dado:

- GLUCOSA: si estuvo alta → habla del orden de los alimentos, el tamaño de las porciones o reducir harinas;
  si estuvo baja → habla de no saltarse comidas o de tener a mano un snack pequeño.
- NUTRICIÓN: si comió muchos carbohidratos → sugiere añadir verdura o proteína al plato;
  si no registró comidas → recuérdale el valor de registrar para detectar patrones.
- ACTIVIDAD FÍSICA: si no hizo nada → propón algo muy concreto y fácil (5 min de estiramiento, bajar las escaleras);
  si hizo mucho → felicítalo y sugiere recuperación activa.
- ESTRÉS (≥7/10): conecta estrés con glucosa y propón una técnica concreta (respiración 4-7-8, pausa de 5 min).
- SUEÑO (<6h): explica su impacto en la glucosa y da un hábito nocturno concreto.
- SÍNTOMAS reportados: prioriza siempre este contexto con un consejo empático y práctico.
- PRESIÓN ARTERIAL / FRECUENCIA CARDÍACA fuera de rango: recomienda mencionárselo al médico con calma.
- AUTOCUIDADO: revisión de pies, hidratación de piel, salud ocular — específico y práctico.
- MEDICIÓN: si los registros son inconsistentes, recuerda el valor de medir siempre a la misma hora.

Si no hay datos suficientes en un área, elige la siguiente más relevante.
NUNCA des consejos genéricos como "toma agua" o "sal a caminar" sin contexto que lo justifique.
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
        $lines[] = '- Tipo de diabetes: ' . ($c['tipo_diabetes'] ?? 'No especificado');
        $lines[] = '- Edad: ' . ($c['edad'] ? $c['edad'] . ' años' : 'No especificada');
        $lines[] = '- Género: ' . ($c['genero'] ?? 'No especificado');
        $lines[] = '- IMC calculado: ' . ($c['imc'] ? $c['imc'] : 'Sin datos de peso/talla');
        $lines[] = '- Peso: ' . ($c['peso_kg'] ? $c['peso_kg'] . ' kg' : 'No registrado');
        $lines[] = '- Rango glucosa objetivo: ' . ($c['rango_glucosa_min'] ?? 70) . '–' . ($c['rango_glucosa_max'] ?? 140) . ' mg/dL';

        // ── Glucosa ─────────────────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== GLUCOSA (últimas 48h) ===';
        $lines[] = '- Promedio: ' . ($c['glucosa_promedio_48h'] !== null ? $c['glucosa_promedio_48h'] . ' mg/dL' : 'Sin registros');
        $lines[] = '- Mínima: ' . ($c['glucosa_min_48h'] !== null ? $c['glucosa_min_48h'] . ' mg/dL' : 'Sin registros');
        $lines[] = '- Máxima: ' . ($c['glucosa_max_48h'] !== null ? $c['glucosa_max_48h'] . ' mg/dL' : 'Sin registros');
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
        $lines[] = '- Frecuencia cardíaca: ' . ($c['frecuencia_cardiaca'] ? $c['frecuencia_cardiaca'] . ' bpm' : 'Sin registro');
        $lines[] = '- Nivel de estrés: ' . ($c['nivel_estres'] ? $c['nivel_estres'] . '/10' : 'Sin registro');
        if ($c['nota_vital'] ?? null) {
            $lines[] = '- Nota del paciente en último registro: "' . $c['nota_vital'] . '"';
        }

        // ── Actividad física ────────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== ACTIVIDAD FÍSICA (ayer) ===';
        $lines[] = '- Minutos de actividad: ' . ($c['minutos_actividad_ayer'] > 0 ? $c['minutos_actividad_ayer'] . ' min' : 'Ninguna');
        $lines[] = '- Tipos de ejercicio: ' . (count($c['tipos_actividad_ayer'] ?? []) > 0 ? implode(', ', $c['tipos_actividad_ayer']) : 'Ninguno');
        $lines[] = '- Intensidad: ' . (count($c['intensidad_actividad'] ?? []) > 0 ? implode(', ', $c['intensidad_actividad']) : 'No especificada');
        $lines[] = '- Energía post-actividad: ' . ($c['energia_post_actividad'] ?? 'No registrada');

        // ── Nutrición ───────────────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== NUTRICIÓN (ayer) ===';
        $lines[] = '- Carbohidratos consumidos: ' . ($c['carbs_ayer_gramos'] > 0 ? $c['carbs_ayer_gramos'] . ' g' : 'Sin registros');
        $lines[] = '- Comidas registradas: ' . (count($c['tipos_comida_ayer'] ?? []) > 0 ? implode(', ', $c['tipos_comida_ayer']) : 'Ninguna');
        $lines[] = '- Grupos de alimentos: ' . (count($c['categorias_alimentos'] ?? []) > 0 ? implode(', ', $c['categorias_alimentos']) : 'No especificados');

        // ── Síntomas ────────────────────────────────────────────────────────
        $lines[] = '';
        $lines[] = '=== SÍNTOMAS RECIENTES (48h) ===';
        $lines[] = count($c['sintomas_recientes'] ?? []) > 0
            ? '- Síntomas reportados: ' . implode(', ', $c['sintomas_recientes'])
            : '- Sin síntomas reportados';

        // ── Último consejo dado (evitar repetición de tema) ─────────────────
        if (!empty($c['ultimo_consejo'])) {
            $lines[] = '';
            $lines[] = '=== ÚLTIMO CONSEJO DADO (NO repetir este tema) ===';
            $lines[] = $c['ultimo_consejo'];
        }

        // ── Instrucción final ───────────────────────────────────────────────
        $lines[] = '';
        $lines[] = 'Analiza los datos anteriores y genera UN mensaje breve, cálido y accionable de máximo 220 caracteres.';
        $lines[] = 'Prioriza el dato más relevante hoy. Si algún valor está fuera de rango, menciónalo con calma y sugiere hablar con el médico. Sin saludos ni títulos.';

        return implode("\n", $lines);
    }

    // extremeGlucoseAlert eliminado: la IA maneja todos los valores con tono cálido y personalizado.
}
