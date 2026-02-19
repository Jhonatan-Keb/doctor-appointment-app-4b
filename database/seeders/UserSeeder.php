<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      User::factory()->create([
            'name' => 'Luis Berdugo',
            'email' => 'luism.berdugo@outlook.com',
            'password'=> bcrypt('12345678'),
            'id_number' => '12345678',
            'phone' => '12345678',
            'address' => 'calle skibidi',
        ])->assignRole('Doctor');  //
    }
}
