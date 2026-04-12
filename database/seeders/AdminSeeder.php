<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear roles por defecto si no existen
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrador del sistema con acceso total a todas las funciones.']
        );

        $doctorRole = Role::firstOrCreate(
            ['name' => 'médico'],
            ['description' => 'Personal médico con acceso a información clínica y métricas de pacientes.']
        );

        // 2. Hacer administrador al primer usuario rígidamente por orden de registro (ID más antiguo)
        $firstUser = User::orderBy('id', 'asc')->first();
        
        if ($firstUser) {
            // Establecer is_admin = true
            $firstUser->update(['is_admin' => true]);
            
            // Asignar rol de admin a través de tabla pivot
            if (!$firstUser->hasRole('admin')) {
                $firstUser->roles()->attach($adminRole->id);
            }
            
            $this->command->info("Se otorgaron privilegios de administrador al usuario: {$firstUser->email}");
        } else {
            $this->command->warn('No se encontraron usuarios en la base de datos para asignar como administrador.');
        }

        $this->command->info('Roles creados y administrador asignado correctamente.');
    }
}
