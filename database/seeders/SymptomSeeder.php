<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    public function run(): void
    {
        $symptoms = [
            // Físicos
            ['name' => 'Sed excesiva (Polidipsia)', 'category' => 'physical'],
            ['name' => 'Micción frecuente (Poliuria)', 'category' => 'physical'],
            ['name' => 'Fatiga', 'category' => 'physical'],
            ['name' => 'Visión borrosa', 'category' => 'physical'],
            ['name' => 'Hambre excesiva (Polifagia)', 'category' => 'physical'],

            // Nocturnos
            ['name' => 'Sudoración nocturna', 'category' => 'nocturnal'],
            ['name' => 'Insomnio', 'category' => 'nocturnal'],
            ['name' => 'Nocturia', 'category' => 'nocturnal'],

            // Neurológicos
            ['name' => 'Hormigueo en extremidades', 'category' => 'neurological'],
            ['name' => 'Mareos', 'category' => 'neurological'],
            ['name' => 'Dolor de cabeza', 'category' => 'neurological'],
            ['name' => 'Entumecimiento', 'category' => 'neurological'],

            // Atípicos
            ['name' => 'Piel seca', 'category' => 'atypical'],
            ['name' => 'Heridas que no sanan', 'category' => 'atypical'],
            ['name' => 'Infecciones frecuentes', 'category' => 'atypical'],
            ['name' => 'Cambios de humor', 'category' => 'atypical'],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::firstOrCreate(
                ['name' => $symptom['name']],
                ['category' => $symptom['category']]
            );
        }
    }
}
