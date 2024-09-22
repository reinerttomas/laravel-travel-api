<?php

declare(strict_types=1);

namespace App\Support\Facades;

use App\Models\User;
use Illuminate\Support\Facades\Auth as LaravelAuth;

final class Auth extends LaravelAuth
{
    /**
     * @throws \Exception
     */
    public static function findOrFail(): User
    {
        $user = LaravelAuth::user();

        if ($user === null) {
            throw new \Exception('User not found');
        }

        return $user;
    }
}
