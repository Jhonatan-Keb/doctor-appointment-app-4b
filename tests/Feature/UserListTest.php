<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('admin sees rendered users from database', function () {

    Role::firstOrCreate(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Creamos varios usuarios reales
    User::factory()->count(5)->create();

    $response = $this->actingAs($admin)
        ->get('/admin/users');

    $response->assertStatus(200);

    // Verificamos que la vista recibió la variable users
    $response->assertViewHas('users');

    $users = $response->viewData('users');

    // Hay usuarios
    expect($users)->not->toBeEmpty();

    // Hay al menos 5
    expect($users->count())->toBeGreaterThanOrEqual(5);

    // Se renderiza un valor real
    $response->assertSee(e($users->first()->email));
});
