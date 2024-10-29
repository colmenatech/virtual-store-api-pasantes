<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llama a los seeders de roles y permisos
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        // Crea un usuario de prueba
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123') // Usa bcrypt para encriptar la contraseña
        ]);
    }
}
