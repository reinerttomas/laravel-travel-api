<?php

declare(strict_types=1);

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\get;

it('returns tours of travel by slug', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    $tour = Tour::factory()->create([
        'travel_id' => $travel->id,
    ]);

    // Act & Assert
    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->has('data', 1)
            ->has('data.0', fn (AssertableJson $json): AssertableJson => $json
                ->where('id', $tour->id)
                ->etc()
            )
            ->etc()
        );
});

it('shows tour price correctly', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    $tour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 123.45,
    ]);

    // Act & Assert
    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->has('data', 1)
            ->has('data.0', fn (AssertableJson $json): AssertableJson => $json
                ->where('price', '123.45')
                ->whereType('price', 'string')
                ->etc()
            )
            ->etc()
        );
});

it('returns tours of travel with pagination', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    Tour::factory()->count(20)->create([
        'travel_id' => $travel->id,
        'price' => 123.45,
    ]);

    // Act & Assert
    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->has('data', 15, fn (AssertableJson $json): AssertableJson => $json
                ->hasAll([
                    'id',
                    'name',
                    'starting_date',
                    'ending_date',
                    'price',
                ])
            )
            ->has('links')
            ->has('meta', fn (AssertableJson $json): AssertableJson => $json
                ->where('last_page', 2)
                ->etc()
            )
        );
});

it('returns tours of travel sort by starting date correctly', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    $laterTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'starting_date' => now()->addDays(2),
        'ending_date' => now()->addDays(3),
    ]);
    $earlierTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'starting_date' => now(),
        'ending_date' => now()->addDay(),
    ]);

    // Act & Assert
    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->has('data', 2)
            ->has('data.0', fn (AssertableJson $json): AssertableJson => $json
                ->where('id', $earlierTour->id)
                ->etc()
            )
            ->has('data.1', fn (AssertableJson $json): AssertableJson => $json
                ->where('id', $laterTour->id)
                ->etc()
            )
            ->etc()
        );
});

it('returns tours of travel sort by price correctly', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    $expensiveTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 200,
    ]);
    $cheapLaterTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 100,
        'starting_date' => now()->addDays(2),
        'ending_date' => now()->addDays(3),
    ]);
    $cheapEarlierTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 100,
        'starting_date' => now(),
        'ending_date' => now()->addDay(),
    ]);

    // Act & Assert
    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours?sortBy=price&sortDirection=asc'))
        ->assertOk()
        ->assertJsonPath('data.0.id', $cheapEarlierTour->id)
        ->assertJsonPath('data.1.id', $cheapLaterTour->id)
        ->assertJsonPath('data.2.id', $expensiveTour->id);
});

it('filters tours of travel by price correctly', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    $expensiveTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 200,
    ]);
    $cheapTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 100,
    ]);

    $endpoint = api()->v1()->endpoint('/travels/' . $travel->slug . '/tours');

    // Act & Assert

    // priceFrom
    $priceFrom = 100;

    expect(get($endpoint . '?priceFrom=' . $priceFrom))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment(['id' => $cheapTour->id])
        ->assertJsonFragment(['id' => $expensiveTour->id]);

    $priceFrom = 150;

    expect(get($endpoint . '?priceFrom=' . $priceFrom))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonMissing(['id' => $cheapTour->id])
        ->assertJsonFragment(['id' => $expensiveTour->id]);

    $priceFrom = 250;

    expect(get($endpoint . '?priceFrom=' . $priceFrom))
        ->assertOk()
        ->assertJsonCount(0, 'data');

    // priceTo
    $priceTo = 200;

    expect(get($endpoint . '?priceTo=' . $priceTo))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment(['id' => $cheapTour->id])
        ->assertJsonFragment(['id' => $expensiveTour->id]);

    $priceTo = 150;

    expect(get($endpoint . '?priceTo=' . $priceTo))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['id' => $cheapTour->id])
        ->assertJsonMissing(['id' => $expensiveTour->id]);

    $priceTo = 50;

    expect(get($endpoint . '?priceTo=' . $priceTo))
        ->assertOk()
        ->assertJsonCount(0, 'data');

    // priceFrom & priceTo
    $priceFrom = 150;
    $priceTo = 250;

    expect(get($endpoint . '?priceFrom=' . $priceFrom . '&priceTo=' . $priceTo))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonMissing(['id' => $cheapTour->id])
        ->assertJsonFragment(['id' => $expensiveTour->id]);
});

it('filters tours of travel by starting date correctly', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    $laterTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'starting_date' => now()->addDays(2),
        'ending_date' => now()->addDays(3),
    ]);
    $earlierTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'starting_date' => now(),
        'ending_date' => now()->addDay(),
    ]);

    $endpoint = api()->v1()->endpoint('/travels/' . $travel->slug . '/tours');

    // Act & Assert

    // startingFrom
    $startingFrom = now();

    expect(get($endpoint . '?startingFrom=' . formatDate($startingFrom)))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment(['id' => $earlierTour->id])
        ->assertJsonFragment(['id' => $laterTour->id]);

    $startingFrom = now()->addDay();

    expect(get($endpoint . '?startingFrom=' . formatDate($startingFrom)))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonMissing(['id' => $earlierTour->id])
        ->assertJsonFragment(['id' => $laterTour->id]);

    $startingFrom = now()->addDays(5);

    expect(get($endpoint . '?startingFrom=' . formatDate($startingFrom)))
        ->assertOk()
        ->assertJsonCount(0, 'data');

    // startingTo
    $startingTo = now()->addDays(5);

    expect(get($endpoint . '?startingTo=' . formatDate($startingTo)))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment(['id' => $earlierTour->id])
        ->assertJsonFragment(['id' => $laterTour->id]);

    $startingTo = now()->addDay();

    expect(get($endpoint . '?startingTo=' . formatDate($startingTo)))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['id' => $earlierTour->id])
        ->assertJsonMissing(['id' => $laterTour->id]);

    $startingTo = now()->subDay();

    expect(get($endpoint . '?startingTo=' . formatDate($startingTo)))
        ->assertOk()
        ->assertJsonCount(0, 'data');

    // startingFrom & startingTo
    $startingFrom = now()->addDay();
    $startingTo = now()->addDays(5);

    expect(get($endpoint . '?startingFrom=' . formatDate($startingFrom) . '&startingTo=' . formatDate($startingTo)))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonMissing(['id' => $earlierTour->id])
        ->assertJsonFragment(['id' => $laterTour->id]);
});

it('it returns validation errors', function (): void {
    // Arrange
    $travel = Travel::factory()->create();

    // Act & Assert
    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours?startingFrom=abc'))
        ->assertUnprocessable();

    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours?priceFrom=abc'))
        ->assertUnprocessable();
});
