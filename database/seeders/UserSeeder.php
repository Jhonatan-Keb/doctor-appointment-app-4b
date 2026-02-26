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
        //
        $doctor = User::factory()->create([
            'name' => 'Hoshiko Kuro',
            'email' => 'hoshiko@dendro.com',
            'password' => bcrypt('12345678'),
            'id_number' => '12345678',
            'phone' => '1234567899',
            'address' => 'Tokyo, Japan',
        ]);
        $doctor->assignRole('Doctor');
        $doctor->doctor()->create();
    }
}
