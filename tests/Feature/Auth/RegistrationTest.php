<?php

test('new users can register', function () {
    $this->seed(\Database\Seeders\RolesSeeder::class);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'user',
        'club' => 'KS Test',
    ]);

    $this->assertAuthenticated();
    $response->assertNoContent();

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 1,
        'klub' => 'KS Test',
    ]);
});
