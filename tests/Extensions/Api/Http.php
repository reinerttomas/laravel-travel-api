<?php

declare(strict_types=1);

namespace Tests\Extensions\Api;

use Illuminate\Testing\TestResponse;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

final readonly class Http
{
    public function __construct(
        private string $prefix
    ) {}

    public function endpoint(string $uri): string
    {
        return $this->prefix . $uri;
    }

    public function get(string $uri): TestResponse
    {
        return get($this->prefix . $uri);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function post(string $uri, array $data = []): TestResponse
    {
        return post($this->prefix . $uri, $data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function put(string $uri, array $data = []): TestResponse
    {
        return put($this->prefix . $uri, $data);
    }

    public function delete(string $uri): TestResponse
    {
        return delete($this->prefix . $uri);
    }
}
