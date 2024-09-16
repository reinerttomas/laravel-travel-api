<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Travel;
use Illuminate\Database\Seeder;

final class DummySeeder extends Seeder
{
    public function run(): void
    {
        $this->travels();
    }

    private function travels(): void
    {
        Travel::create([
            'name' => 'First travel',
            'description' => 'Greet offer!',
            'is_public' => true,
            'number_of_days' => 5,
        ]);

        Travel::create([
            'name' => 'Second travel',
            'description' => 'Good offer!',
            'is_public' => true,
            'number_of_days' => 1,
        ]);

        Travel::create([
            'name' => 'Third travel',
            'description' => 'Nice trip!',
            'is_public' => false,
            'number_of_days' => 3,
        ]);
    }
}
