<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear los 5 roles base
        $roles = [
            ['nombre' => 'admin', 'descripcion' => 'Administrador general del sistema'],
            ['nombre' => 'presidente', 'descripcion' => 'Presidente/a de la Junta de Vecinos'],
            ['nombre' => 'secretario', 'descripcion' => 'Secretario/a de la Junta de Vecinos'],
            ['nombre' => 'tesorero', 'descripcion' => 'Tesorero/a de la Junta de Vecinos'],
            ['nombre' => 'vecino', 'descripcion' => 'Vecino o socio registrado en la comunidad'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(['nombre' => $roleData['nombre']], $roleData);
        }

        // Crear usuario administrador inicial
        $roleAdmin = Role::where('nombre', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@jjvv.cl'],
            [
                'name' => 'Administrador JJVV',
                'password' => Hash::make('password123'),
                'role_id' => $roleAdmin->id,
            ]
        );
    }
}