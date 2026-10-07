<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

test('seeded admin can access the admin panel', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::where('email', 'admin@example.com')->first();

    $this->actingAs($admin)->get('/admin')->assertOk();
});

test('seeded regular user cannot access the admin panel', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::where('email', 'user@example.com')->first();

    $this->actingAs($user)->get('/admin')->assertForbidden();
});
