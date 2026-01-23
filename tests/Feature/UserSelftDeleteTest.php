<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

//User la funcion para refrescar la DB
uses(RefreshDatabase::class);

test('Un usuario no puede eliminarse asi mismo', function () {
    // 1) Crear un usuario de prueba
    $user = User::factory()->create();

    //2) Simular que ese usuario ya inicio sesion
    $this->actingAs($user, 'web');
    //3) Simular una peticon HTTP DELETE (borrar un usuario)
    $response = $this->delete(route('admin.users.destroy', $user));
    //4) Esperar que el servidor bloquee el borrado asi mismo
    $response->assertStatus(403);
    //5) Verificar en la DB que el usuario sigue existiendo
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});