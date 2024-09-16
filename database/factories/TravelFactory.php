<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Travel>
 */
final class TravelFactory extends Factory
{
    protected $model = Travel::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'description' => fake()->text(),
            'is_public' => fake()->boolean(),
            'number_of_days' => fake()->randomNumber(),
        ];
    }
}
