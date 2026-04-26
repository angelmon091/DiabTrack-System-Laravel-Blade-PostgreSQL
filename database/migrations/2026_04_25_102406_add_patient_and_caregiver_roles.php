<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')->insertOrIgnore([
            [
                'name' => 'paciente',
                'description' => 'Paciente con diabetes que registra y monitorea sus datos de salud.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'cuidador',
                'description' => 'Familiar o acompañante que monitorea la salud de un paciente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('roles')->whereIn('name', ['paciente', 'cuidador'])->delete();
    }
};
