<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

final readonly class AccessTokenData
{
    public function __construct(
        public string $accessToken,
    ) {}
}
