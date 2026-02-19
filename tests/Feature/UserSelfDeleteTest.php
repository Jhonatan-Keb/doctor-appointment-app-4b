<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSelfDeleteTest extends TestCase
{
    //Usamos la cualiada para refrescar la base de datos entre test
    use RefreshDatabase;

    public function test_un_usuario_no_puede_eliminarse_a_si_mismo()
    {
        // Creamos un usuario de prueba
        $user = User::factory()->create();

        // Simulamos que el usuario está logeado
        $this->actingAs($user);

        //Simular una petición DELETE a la ruta de eliminación del usuario
        $response = $this->delete(route('admin.users.destroy', $user));

        //Esperamos que el servidor responda con un código 403 (Prohibido)
        $response->assertStatus(403);

        //Verificamos que el usuario aún existe en la base de datos
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}