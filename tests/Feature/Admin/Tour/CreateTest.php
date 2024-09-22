<?php

declare(strict_types=1);

use App\Models\Travel;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('cannot create a tour of travel as guest', function (): void {
    // Arrange
    $travel = Travel::factory()->create();

    // Act & Assert
    api()->v1()->post('/admin/travels/' . $travel->id . '/tours')
        ->assertUnauthorized();
});

it('cannot create a tour of travel as non admin', function (): void {
    // Arrange
    $user = User::factory()->editor()->create();
    $travel = Travel::factory()->create();

    actingAs($user);

    // Act & Assert
    api()->v1()->post('/admin/travels/' . $travel->id . '/tours')
        ->assertForbidden();
});

it('can create a tour of travel', function (array $data): void {
    // Arrange
    $user = User::factory()->admin()->create();
    $travel = Travel::factory()->create();

    actingAs($user);

    // Act & Assert
    api()->v1()->post('/admin/travels/' . $travel->id . '/tours', $data)
        ->assertCreated()
        ->assertJsonFragment([
            'name' => $data['name'],
        ]);
})->with([
    fn (): array => [
        'name' => 'Tour name',
        'starting_date' => now()->toDateString(),
        'ending_date' => now()->addDay()->toDateString(),
        'price' => 123.45,
    ],
]);

it('cannot create a tour of travel with invalid data', function (array $data): void {
    // Arrange
    $user = User::factory()->admin()->create();
    $travel = Travel::factory()->create();

    actingAs($user);

    // Act & Assert
    api()->v1()->post('/admin/travels/' . $travel->id . '/tours', $data)
        ->assertUnprocessable();
})->with([
    fn (): array => [
        'name' => 'Tour name',
    ],
]);
