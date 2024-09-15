<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
            'name' => $this->faker->name(), //
            'starting_date' => Carbon::now(),
            'ending_date' => Carbon::now(),
            'price' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'travel_id' => Travel::factory(),
        ];
    }
}
