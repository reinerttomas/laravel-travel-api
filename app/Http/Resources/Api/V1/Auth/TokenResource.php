<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Auth;

use App\DataTransferObjects\AccessTokenData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AccessTokenData
 */
final class TokenResource extends JsonResource
{
    public static $wrap;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->accessToken,
        ];
    }
}
