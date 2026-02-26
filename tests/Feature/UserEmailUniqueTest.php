<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Issue 1: Email Unique - no se puede registrar un usuario con email duplicado', function () {
    User::factory()->create(['email' => 'duplicado@test.com']);

    $response = $this->post('/register', [
        'name' => 'Nuevo Usuario',
        'email' => 'duplicado@test.com', // Ya existe
        'password' => 'password',
        'password_confirmation' => 'password',
        'id_number' => '1234567890',
        'phone' => '5555555555',
        'address' => 'Calle Falsa 123',
    ]);

    $response->assertSessionHasErrors(['email']);
});
