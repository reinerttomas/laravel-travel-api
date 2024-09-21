<?php

declare(strict_types=1);

namespace App\Builders\Queries;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Role>
 */
final class RoleBuilder extends Builder
{
    public function whereName(string $name): self
    {
        return $this->where('name', $name);
    }
}
