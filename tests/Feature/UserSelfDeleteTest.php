<?php
use  App\Models\User;
Use Illuminate\Foundation\Testing\RefreshDatabase;

//se refresca la base de datos en cada test
uses(RefreshDatabase::class);

test('un usuario no puede eliminarse a si mismo', function () {
   
// Creacion del usuario de prueba
    $user = User::factory()->create();

//simulamos que ya iniciamos sesion con ese usuario
    $this->actingAs($user, 'web');

// Intentamos eliminar al mismo usuario
    $response = $this->delete(route('admin.users.destroy', $user->id));

// Esperamos que el servidor bloque la accion 
$response->assertStatus(403);
    
// Verificamos que el usuario sigue existiendo en la base de datos
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});
