<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\Queries\Order\Direction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ToursListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'startingFrom' => 'date',
            'startingTo' => 'date',
            'sortBy' => Rule::in(['price']),
            'sortDirection' => ['required_with:sortBy', Rule::in(Direction::cases())],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'sortBy' => 'The "sortBy" parameter accepts only "price" value',
            'sortDirection' => [
                'in' => 'The "sortDirection" parameter accepts only "asc" and "desc" values',
                'required_with' => 'The "sortDirection" parameter is required when "sortBy" is provided',
            ],
        ];
    }
}
