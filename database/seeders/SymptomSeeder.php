<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Symptom;

class SymptomSeeder extends Seeder
{
    public function run(): void
    {
        $symptoms = [
            ['name' => 'Sudoración fría', 'category' => 'physical'],
            ['name' => 'Temblores', 'category' => 'physical'],
            ['name' => 'Hambre repentina', 'category' => 'physical'],
            ['name' => 'Palpitaciones', 'category' => 'physical'],
            ['name' => 'Ansiedad', 'category' => 'physical'],
            
            ['name' => 'Pesadillas', 'category' => 'nocturnal'],
            ['name' => 'Sudoración Nocturna', 'category' => 'nocturnal'],
            ['name' => 'Dolor de cabeza matutino', 'category' => 'nocturnal'],
            ['name' => 'Cansancio al despertar', 'category' => 'nocturnal'],
            
            ['name' => 'Confusión', 'category' => 'neurological'],
            ['name' => 'Visión borrosa', 'category' => 'neurological'],
            ['name' => 'Dificultad para hablar', 'category' => 'neurological'],
            ['name' => 'Debilidad', 'category' => 'neurological'],
            ['name' => 'Mareo', 'category' => 'neurological'],
            
            ['name' => 'Cambios de Humor', 'category' => 'atypical'],
            ['name' => 'Náuseas', 'category' => 'atypical'],
            ['name' => 'Hormigueo en labios y/o lengua', 'category' => 'atypical'],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::updateOrCreate(['name' => $symptom['name']], $symptom);
        }
    }
}
