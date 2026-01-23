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
            'name' => 'Hoshiko Kuro',
            'email' => 'hoshiko@dendro.com',
            'password' => bcrypt('KuroHoshiko12!'),
            'id_number' => '123456789',
            'phone' => '5555555555',
            'adress' => 'Kanayork',
        ])->assignRole('Administrador');
    }
}
