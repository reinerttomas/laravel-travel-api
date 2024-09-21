<?php

declare(strict_types=1);

use App\Models\Travel;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\get;

it('returns list of travels with pagination', function (): void {
    // Arrange
    Travel::factory()->count(20)->public()->create();

    // Act & Assert
    expect(get('/api/v1/travels'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->has('data', 15, fn (AssertableJson $json): AssertableJson => $json
                ->hasAll([
                    'id',
                    'name',
                    'slug',
                    'description',
                    'number_of_days',
                    'number_of_nights',
                ])
            )
            ->has('links')
            ->has('meta', fn (AssertableJson $json): AssertableJson => $json
                ->where('last_page', 2)
                ->etc()
            )
        );
});

it('returns list of public travels', function (): void {
    // Arrange
    $notPublicTravel = Travel::factory()->notPublic()->create();
    $publicTravel = Travel::factory()->public()->create();

    // Act & Assert
    expect(get('/api/v1/travels'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->has('data', 1)
            ->has('data.0', fn (AssertableJson $json): AssertableJson => $json
                ->where('id', $publicTravel->id)
                ->whereNot('id', $notPublicTravel->id)
                ->etc()
            )
            ->etc()
        );
});
