<?php

declare(strict_types=1);

namespace App\ValueObjects;

final readonly class AccessToken
{
    public function __construct(
        public string $accessToken,
    ) {}
}
