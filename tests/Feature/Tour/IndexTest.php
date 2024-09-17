<?php

declare(strict_types=1);

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\get;

it('returns tours of travel by slug', function () {
    // Arrange
    $travel = Travel::factory()->create();
    $tour = Tour::factory()->create([
        'travel_id' => $travel->id,
    ]);

    // Act & Assert
    expect(get('/api/v1/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('id', $tour->id)
                ->etc()
            )
            ->etc()
        );
});

it('shows tour price correctly', function () {
    // Arrange
    $travel = Travel::factory()->create();
    $tour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 123.45,
    ]);

    // Act & Assert
    expect(get('/api/v1/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json
                ->where('price', '123.45')
                ->whereType('price', 'string')
                ->etc()
            )
            ->etc()
        );
});

it('returns tours of travel with pagination', function () {
    // Arrange
    $travel = Travel::factory()->create();
    Tour::factory()->count(20)->create([
        'travel_id' => $travel->id,
        'price' => 123.45,
    ]);

    // Act & Assert
    expect(get('/api/v1/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 15, fn (AssertableJson $json) => $json
                ->hasAll([
                    'id',
                    'name',
                    'starting_date',
                    'ending_date',
                    'price',
                ])
            )
            ->has('links')
            ->has('meta', fn (AssertableJson $json) => $json
                ->where('last_page', 2)
                ->etc()
            )
        );
});
