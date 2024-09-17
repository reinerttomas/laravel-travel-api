<?php

declare(strict_types=1);

namespace App\Builders\Queries;

use App\Enums\Queries\Order\Direction;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Tour>
 */
final class TourBuilder extends Builder
{
    public function orderByStartingDate(Direction $direction = Direction::ASC): self
    {
        return $this->orderBy('starting_date', $direction->value);
    }
}
