<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreateWithDuplicateEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_creation_fails_when_email_already_exists()
    {
        // Crear un usuario con un email específico
        $existingUser = User::factory()->create([
            'email' => 'existing@example.com'
        ]);

        // Intentar crear otro usuario con el mismo email
        $response = $this->actingAs($existingUser)->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'id_number' => 'NEW12345',
            'phone' => '1234567890',
            'address' => 'New Address',
            'role_id' => 1
        ]);

        // Verificar que hay un error de validación en el campo email
        $response->assertSessionHasErrors('email');

        // Verificar que solo existe un usuario con ese email
        $this->assertEquals(1, User::where('email', 'existing@example.com')->count());
    }
}