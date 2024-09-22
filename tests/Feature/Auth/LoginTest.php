<?php

declare(strict_types=1);

use App\Models\User;

it('returns token with valid credentials', function (): void {
    // Arrange
    $user = User::factory()->create();

    $data = [
        'email' => $user->email,
        'password' => 'password',
    ];

    // Act & Assert
    api()->v1()->post('/login', $data)
        ->assertCreated()
        ->assertJsonStructure([
            'access_token',
        ]);
});

it('returns errors with invalid credentials', function (): void {
    // Arrange
    $data = [
        'email' => 'nonexisting@user.com',
        'password' => 'password',
    ];

    // Act & Assert
    api()->v1()->post('/login', $data)
        ->assertUnprocessable();
});
