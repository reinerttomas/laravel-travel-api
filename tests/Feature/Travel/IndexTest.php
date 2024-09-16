<?php

declare(strict_types=1);

use App\Models\Travel;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\get;

it('returns list of travels with pagination', function () {
    // Arrange
    Travel::factory()->count(20)->create([
        'is_public' => true,
    ]);

    // Act & Assert
    expect(get('/api/v1/travels'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 15, fn (AssertableJson $json) => $json
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
            ->has('meta', fn (AssertableJson $json) => $json
                ->where('last_page', 2)
                ->etc()
            )
        );
});

it('returns list of public travels', function () {
    // Arrange
    Travel::factory()->create([
        'is_public' => false,
    ]);
    $publicTravel = Travel::factory()->create([
        'is_public' => true,
    ]);

    // Act & Assert
    expect(get('/api/v1/travels'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('id', $publicTravel->id)
                ->etc()
            )
            ->etc()
        );
});
