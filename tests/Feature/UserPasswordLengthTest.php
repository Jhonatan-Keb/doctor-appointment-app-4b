<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Issue 2: Minimum Password Length - la contraseña debe tener al menos 8 caracteres', function () {
    $response = $this->post('/register', [
        'name' => 'Nuevo Usuario',
        'email' => 'test@test.com',
        'password' => 'short', // 5 caracteres
        'password_confirmation' => 'short',
        'id_number' => '1234567890',
        'phone' => '5555555555',
        'address' => 'Calle Falsa 123',
    ]);

    $response->assertSessionHasErrors(['password']);
});
