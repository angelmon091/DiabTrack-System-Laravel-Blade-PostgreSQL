<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TipService
{
    public function generarTip(array $datosPaciente): array
    {
        $tipLocal = $this->generarTipLocal($datosPaciente);

        try {
            return $this->generarConClaude($datosPaciente);
        } catch (\Throwable $e) {
            Log::error('TipService: error al generar tip con Anthropic', [
                'error' => $e->getMessage(),
                'datos_paciente' => $datosPaciente,
            ]);

            try {
                return $this->generarConGemini($datosPaciente);
            } catch (\Throwable $geminiError) {
                Log::error('TipService: error al generar tip con Gemini', [
                    'error' => $geminiError->getMessage(),
                    'datos_paciente' => $datosPaciente,
                ]);
            }

            Log::warning('TipService: usando reglas locales como respaldo', [
                'datos_paciente' => $datosPaciente,
            ]);

            return $tipLocal;
        }
    }

    private function generarTipLocal(array $datosPaciente): array
    {
        $glucosa = (float) ($datosPaciente['glucosa'] ?? 0);

        if ($glucosa > 180) {
            return [
                'tip' => 'Tu glucosa está alta. Sigue las indicaciones de tu médico y prioriza hidratación y control.',
                'fuente' => 'reglas_locales',
                'label' => 'Consejo generado por reglas clínicas',
            ];
        }

        if ($glucosa > 0 && $glucosa < 70) {
            return [
                'tip' => 'Tu glucosa está baja. Toma las medidas recomendadas por tu médico cuanto antes.',
                'fuente' => 'reglas_locales',
                'label' => 'Consejo generado por reglas clínicas',
            ];
        }

        return [
            'tip' => 'Vas por buen camino. Mantén tu rutina de alimentación, hidratación y actividad física.',
            'fuente' => 'reglas_locales',
            'label' => 'Consejo generado por reglas clínicas',
        ];
    }

    private function generarConClaude(array $datosPaciente): array
    {
        $response = Http::timeout(10)
            ->withHeaders([
                'x-api-key' => env('ANTHROPIC_API_KEY'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-haiku-4-5',
                'max_tokens' => 300,
                'system' => 'Eres un asistente clínico especializado en diabetes. Responde en español, breve y empático.',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $this->construirPromptUsuario($datosPaciente),
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                'Anthropic respondió con estado HTTP ' . $response->status() . ' y cuerpo: ' . $response->body()
            );
        }

        $tip = trim(collect($response->json('content', []))->pluck('text')->filter()->implode(' '));

        if ($tip === '') {
            throw new \RuntimeException('Anthropic devolvió una respuesta vacía.');
        }

        return [
            'tip' => $tip,
            'fuente' => 'ia_generativa',
            'label' => 'Consejo generado por IA (Claude)',
        ];
    }

    private function generarConGemini(array $datosPaciente): array
    {
        $geminiKey = env('GEMINI_API_KEY');

        if (!$geminiKey) {
            throw new \RuntimeException('GEMINI_API_KEY no está configurada.');
        }

        $response = Http::timeout(10)
            ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . urlencode($geminiKey), [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $this->construirPromptUsuario($datosPaciente),
                            ],
                        ],
                    ],
                ],
                'systemInstruction' => [
                    'parts' => [
                        [
                            'text' => 'Eres un asistente clínico de diabetes. Responde en español, breve y empático.',
                        ],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 300,
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                'Gemini respondió con estado HTTP ' . $response->status() . ' y cuerpo: ' . $response->body()
            );
        }

        $tip = trim((string) data_get($response->json(), 'candidates.0.content.parts.0.text', ''));

        if ($tip === '') {
            throw new \RuntimeException('Gemini devolvió una respuesta vacía.');
        }

        return [
            'tip' => $tip,
            'fuente' => 'ia_generativa',
            'label' => 'Consejo generado por IA (Gemini)',
        ];
    }

    private function construirPromptUsuario(array $datosPaciente): string
    {
        return sprintf(
            "Datos del paciente:\nTipo de diabetes: %s\nEdad: %s\nGlucosa: %s mg/dL\nHbA1c: %s%%\nIMC: %s",
            $datosPaciente['tipo_diabetes'] ?? 'No disponible',
            $datosPaciente['edad'] ?? 'No disponible',
            $datosPaciente['glucosa'] ?? 'No disponible',
            $datosPaciente['hba1c'] ?? 'No disponible',
            $datosPaciente['imc'] ?? 'No disponible',
        );
    }
}