<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\TravelRequest;
use App\Http\Resources\Api\V1\TravelResource;
use App\Models\Travel;

final class TravelController extends Controller
{
    public function store(TravelRequest $request): TravelResource
    {
        $travel = Travel::create($request->validated());

        return TravelResource::make($travel);
    }

    public function update(Travel $travel, TravelRequest $request): TravelResource
    {
        $travel->update($request->validated());

        return TravelResource::make($travel);
    }
}
