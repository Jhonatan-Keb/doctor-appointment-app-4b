<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Issue 3: Phone Format - el telefono debe ser numerico', function () {
    $response = $this->post('/register', [
        'name' => 'Nuevo Usuario',
        'email' => 'test@test.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'id_number' => '1234567890',
        'phone' => '555-1234', // Formato inválido (tiene guión)
        'address' => 'Calle Falsa 123',
    ]);

    $response->assertSessionHasErrors(['phone']);
});
