<?php

declare(strict_types=1);

namespace App\Actions;

use App\DataTransferObjects\AccessTokenData;
use App\DataTransferObjects\CreateAccessTokenData;
use App\Models\User;
use App\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final readonly class CreateAccessTokenAction
{
    public function execute(User $user, CreateAccessTokenData $data): AccessTokenData
    {
        $attempt = Auth::attempt([
            'email' => $data->email,
            'password' => $data->password,
        ]);

        if (! $attempt) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return new AccessTokenData(
            $user->createToken($this->device($data->userAgent))->plainTextToken
        );
    }

    private function device(?string $userAgent): string
    {
        return substr($userAgent ?? '', 0, 255);
    }
}
