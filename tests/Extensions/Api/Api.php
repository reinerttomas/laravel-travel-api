<?php

declare(strict_types=1);

namespace Tests\Extensions\Api;

final readonly class Api
{
    public function __construct(
        private string $prefix = '/api',
    ) {}

    public function v1(): Http
    {
        return $this->client('v1');
    }

    public function v2(): Http
    {
        return $this->client('v2');
    }

    private function client(string $version): Http
    {
        return new Http($this->prefix . '/' . $version);
    }
}
