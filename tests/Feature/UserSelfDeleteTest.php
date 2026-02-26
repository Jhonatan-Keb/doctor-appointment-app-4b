<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

//usar la fucion para refrescar BF

uses(RefreshDatabase::class);

test('Un usuario no puede eliminarse a si mismo', function () {
    //1) crear un usuario de prueba
    $user = User::factory()->create();

    //2) simular que ese usuario ya inicio secion
    $this->actingAs($user, 'web');

    //3) simular una peticion HTTP DELETE (borrar un usuario)
    $response = $this->delete(route('admin.admin.users.destroy', $user));

    //4) esperar que el servidor bloquee el borrado a si mismo
    $response->assertStatus(403);

    //5) verificar en la base de datos que sigue existiendo
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);

});
