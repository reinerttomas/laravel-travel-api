<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tour>
 */
final class TourFactory extends Factory
{
    protected $model = Tour::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'starting_date' => now(),
            'ending_date' => now()->addDays(fake()->randomDigitNotZero()),
            'price' => fake()->randomFloat(2, 10, 999),
        ];
    }
}
