<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Crear un usuario de prueba cada que se ejecuten migraciones
        //php artisan migrate:fresh --seed
        User::factory()->create([
            'name' => 'Joel Sanchez',
            'email' => 'joel@example.com',
            'password' => bcrypt('12345678'),
            'id_numero' => '1234567890',
            'phone' => '123-456-7890',
            'address' => 'MAXCANU city', 
            // Asignar el ID del rol correspondiente
        ])->assignRole('Doctor');
    }
}
