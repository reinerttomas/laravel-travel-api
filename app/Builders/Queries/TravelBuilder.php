<?php

declare(strict_types=1);

namespace App\Builders\Queries;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Travel>
 */
final class TravelBuilder extends Builder
{
    public function wherePublic(bool $isPublic = true): self
    {
        return $this->where('is_public', $isPublic);
    }
}
