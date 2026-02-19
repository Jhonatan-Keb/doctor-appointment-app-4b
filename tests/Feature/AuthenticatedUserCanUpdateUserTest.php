<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticatedUserCanUpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_a_user_record()
    {
        // Crear un rol
        $role = Role::create(['name' => 'Administrador']);

        // Crear un usuario autenticado
        $admin = User::factory()->create();

        // Crear un usuario que será actualizado con todos los campos requeridos
        $userToUpdate = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'id_number' => 'ORIG12345',
            'phone' => '1234567890',
            'address' => 'Original Address'
        ]);

        // Actualizar el usuario
        $response = $this->actingAs($admin)->put(route('admin.users.update', $userToUpdate), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'id_number' => 'UPD12345',
            'phone' => '9876543210',
            'address' => 'Updated Address',
            'role_id' => $role->id
        ]);

        // Verificar que la actualización fue exitosa
        $response->assertRedirect();

        // Verificar que los cambios se guardaron en la base de datos
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }
}
