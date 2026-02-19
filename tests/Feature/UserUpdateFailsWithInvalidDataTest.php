<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUpdateFailsWithInvalidDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_fails_with_invalid_email()
    {
        // Crear un usuario autenticado
        $admin = User::factory()->create();

        // Crear un usuario que será actualizado
        $userToUpdate = User::factory()->create();

        // Intentar actualizar con email inválido
        $response = $this->actingAs($admin)->put(route('admin.users.update', $userToUpdate), [
            'name' => 'Valid Name',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'id_number' => 'VAL12345',
            'phone' => '1234567890',
            'address' => 'Valid Address',
            'role_id' => 1
        ]);

        // Verificar que hay error de validación
        $response->assertSessionHasErrors('email');
    }

    public function test_update_fails_when_name_is_too_short()
    {
        // Crear un usuario autenticado
        $admin = User::factory()->create();

        // Crear un usuario que será actualizado
        $userToUpdate = User::factory()->create();

        // Intentar actualizar con nombre muy corto
        $response = $this->actingAs($admin)->put(route('admin.users.update', $userToUpdate), [
            'name' => 'AB',
            'email' => 'valid@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'id_number' => 'VAL12345',
            'phone' => '1234567890',
            'address' => 'Valid Address',
            'role_id' => 1
        ]);

        // Verificar que hay error de validación
        $response->assertSessionHasErrors('name');

        // Verificar que el usuario no se actualizó
        $this->assertDatabaseMissing('users', [
            'id' => $userToUpdate->id,
            'name' => 'AB'
        ]);
    }
}