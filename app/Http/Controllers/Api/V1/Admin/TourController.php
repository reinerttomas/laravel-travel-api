<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\TourRequest;
use App\Http\Resources\Api\V1\TourResource;
use App\Models\Travel;

final class TourController extends Controller
{
    public function store(Travel $travel, TourRequest $request): TourResource
    {
        $tour = $travel->tours()->create($request->validated());

        return TourResource::make($tour);
    }
}
