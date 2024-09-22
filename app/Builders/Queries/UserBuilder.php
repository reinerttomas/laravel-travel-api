<?php

declare(strict_types=1);

namespace App\Builders\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<User>
 */
final class UserBuilder extends Builder
{
    public function whereEmail(string $email): self
    {
        return $this->where('email', $email);
    }
}
