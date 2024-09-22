<?php

declare(strict_types=1);

use App\Models\Travel;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('can update the travel', function (array $data): void {
    // Arrange
    $user = User::factory()->editor()->create();
    $travel = Travel::factory()->create();

    actingAs($user);

    // Act & Assert
    api()->v1()->put('/admin/travels/' . $travel->id, $data)
        ->assertOk()
        ->assertJsonFragment([
            'name' => $data['name'],
        ]);
})->with([
    fn (): array => [
        'name' => 'Travel name updated',
        'is_public' => 1,
        'description' => 'Some description',
        'number_of_days' => 5,
    ],
]);

it('cannot update the travel with invalid data', function (array $data): void {
    // Arrange
    $user = User::factory()->editor()->create();
    $travel = Travel::factory()->create();

    actingAs($user);

    // Act & Assert
    api()->v1()->put('/admin/travels/' . $travel->id, $data)
        ->assertUnprocessable();
})->with([
    fn (): array => [
        'name' => 'Travel name',
    ],
]);
