<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('cannot create a travel as guest', function (): void {
    // Act
    post('/api/v1/admin/travels')
        ->assertUnauthorized();
});

it('cannot create a travel as non admin', function (): void {
    // Arrange
    $user = User::factory()->editor()->create();

    actingAs($user);

    // Act & Assert
    post('/api/v1/admin/travels')
        ->assertForbidden();
});

it('can create a travel', function (array $data): void {
    // Arrange
    $user = User::factory()->admin()->create();

    actingAs($user);

    // Act & Assert
    post('/api/v1/admin/travels', $data)
        ->assertCreated()
        ->assertJsonFragment([
            'name' => $data['name'],
        ]);
})->with([
    fn (): array => [
        'name' => 'Travel name',
        'description' => 'Some description',
        'is_public' => true,
        'number_of_days' => 5,
    ],
]);
