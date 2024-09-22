<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\CreateAccessTokenAction;
use App\DataTransferObjects\CreateAccessTokenData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\Api\V1\Auth\TokenResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

final class LoginController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(
        LoginRequest $request,
        CreateAccessTokenAction $createAccessTokenAction,
    ): JsonResponse {
        $user = User::whereEmail($request->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $tokenData = $createAccessTokenAction->execute(
            $user,
            new CreateAccessTokenData(
                $request->email,
                $request->password,
                $request->userAgent() ?? '',
            )
        );

        return TokenResource::make($tokenData)
            ->response()
            ->setStatusCode(201);
    }
}
