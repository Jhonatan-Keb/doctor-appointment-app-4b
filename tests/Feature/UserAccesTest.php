<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Asegura roles para todos los tests
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'doctor']);
});

test('guest is redirected to login when visiting admin users', function () {
    $this->get('/admin/users')
        ->assertStatus(302)
        ->assertRedirect('/login');
});

test('admin can access admin users page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin/users')
        ->assertStatus(200);
});


test('admin sees rendered users from database (not hardcoded)', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    User::factory()->count(5)->create();

    $response = $this->actingAs($admin)->get('/admin/users');

    $response->assertStatus(200);

    // Si tu index ahora manda $users a la vista, esto ya pasa:
    $response->assertViewHas('users');

    $users = $response->viewData('users');

    expect($users)->not->toBeEmpty();
    expect($users->count())->toBeGreaterThanOrEqual(5);

    // Renderiza un valor real (no inventado)
    $response->assertSee(e($users->first()->email));
});
