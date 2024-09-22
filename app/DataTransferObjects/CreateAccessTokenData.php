<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

final readonly class CreateAccessTokenData
{
    public function __construct(
        public string $email,
        public string $password,
        public string $userAgent,
    ) {}
}
