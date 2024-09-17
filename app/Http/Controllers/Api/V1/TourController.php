<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TourResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TourController extends Controller
{
    public function index(Travel $travel): AnonymousResourceCollection
    {
        $tours = $travel->tours()
            ->orderByStartingDate()
            ->paginate();

        return TourResource::collection($tours);
    }
}
