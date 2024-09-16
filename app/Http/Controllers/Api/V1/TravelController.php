<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TravelController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $travels = Travel::query()->wherePublic()->paginate();

        return TravelResource::collection($travels);
    }
}
