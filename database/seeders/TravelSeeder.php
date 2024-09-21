<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Travel;
use Illuminate\Database\Seeder;

final class TravelSeeder extends Seeder
{
    public function run(): void
    {
        $this->travels();
    }

    private function travels(): void
    {
        $travel1 = Travel::create([
            'name' => 'First travel',
            'description' => 'Greet offer!',
            'is_public' => true,
            'number_of_days' => 5,
        ]);

        $travel1->tours()->create([
            'name' => 'Tour on Sunday',
            'starting_date' => '2024-09-15',
            'ending_date' => '2024-09-20',
            'price' => 99.99,
        ]);

        $travel1->tours()->create([
            'name' => 'Tour on Wednesday',
            'starting_date' => '2024-09-18',
            'ending_date' => '2024-09-23',
            'price' => 89.99,
        ]);

        $travel2 = Travel::create([
            'name' => 'Second travel',
            'description' => 'Good offer!',
            'is_public' => true,
            'number_of_days' => 1,
        ]);

        $travel2->tours()->create([
            'name' => 'Tour on Monday',
            'starting_date' => '2024-09-16',
            'ending_date' => '2024-09-17',
            'price' => 9.99,
        ]);

        $travel2->tours()->create([
            'name' => 'Tour on Wednesday',
            'starting_date' => '2024-09-18',
            'ending_date' => '2024-09-19',
            'price' => 8.99,
        ]);

        $travel2->tours()->create([
            'name' => 'Tour on Friday',
            'starting_date' => '2024-09-20',
            'ending_date' => '2024-09-21',
            'price' => 10.99,
        ]);

        $travel3 = Travel::create([
            'name' => 'Third travel',
            'description' => 'Nice trip!',
            'is_public' => false,
            'number_of_days' => 3,
        ]);

        $travel3->tours()->create([
            'name' => 'Tour on Monday',
            'starting_date' => '2024-09-16',
            'ending_date' => '2024-09-19',
            'price' => 49.99,
        ]);
    }
}
