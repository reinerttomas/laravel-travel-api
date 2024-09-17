<?php

declare(strict_types=1);

namespace App\Enums\Queries\Order;

enum Direction: string
{
    case ASC = 'asc';
    case DESC = 'desc';
}
