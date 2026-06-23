<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$payload = [
    'model' => config('services.anthropic.model', 'claude-haiku-4-5'),
    'max_tokens' => 100,
    'messages' => [[
        'role' => 'user',
        'content' => 'Prueba de conexión desde script PHP en contenedor',
    ]],
];

try {
    $response = Http::timeout(15)
        ->withHeaders([
            'x-api-key' => config('services.anthropic.key'),
            'anthropic-version' => config('services.anthropic.version', '2023-06-01'),
            'Content-Type' => 'application/json',
        ])
        ->post('https://api.anthropic.com/v1/messages', $payload);

    echo "HTTP/" . $response->status() . "\n";
    echo $response->body() . "\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
