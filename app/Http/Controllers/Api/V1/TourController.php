<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Builders\Queries\TourBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ToursListRequest;
use App\Http\Resources\Api\V1\TourResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TourController extends Controller
{
    public function index(Travel $travel, ToursListRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();

        if (isset($data['priceFrom'])) {
            $data['priceFrom'] *= 100;
        }

        if (isset($data['priceTo'])) {
            $data['priceTo'] *= 100;
        }

        $tours = $travel->tours()
            ->when(isset($data['priceFrom']), fn (TourBuilder $query): TourBuilder => $query->wherePriceFrom($data['priceFrom']))
            ->when(isset($data['priceTo']), fn (TourBuilder $query): TourBuilder => $query->wherePriceTo($data['priceTo']))
            ->when(isset($data['startingFrom']), fn (TourBuilder $query): TourBuilder => $query->whereStartingFrom($data['startingFrom']))
            ->when(isset($data['startingTo']), fn (TourBuilder $query): TourBuilder => $query->whereStartingTo($data['startingTo']))
            ->when(isset($data['sortBy']), fn (TourBuilder $query): TourBuilder => $query->orderBy($data['sortBy'], $data['sortDirection']))
            ->orderByStartingDate()
            ->paginate();

        return TourResource::collection($tours);
    }
}
