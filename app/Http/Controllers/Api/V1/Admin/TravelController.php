<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\CreateTravelRequest;
use App\Http\Resources\Api\V1\TravelResource;
use App\Models\Travel;

final class TravelController extends Controller
{
    public function store(CreateTravelRequest $request): TravelResource
    {
        $travel = Travel::create($request->validated());

        return TravelResource::make($travel);
    }
}
