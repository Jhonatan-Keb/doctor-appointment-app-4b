<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('admin can delete user', function () {

    Role::firstOrCreate(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();

    $this->actingAs($admin)
        ->delete("/admin/users/{$user->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('users', [
        'id' => $user->id
    ]);
});
