<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\Queries\TourBuilder;
use Database\Factories\TourFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Tour extends Model
{
    /**
     * @phpstan-use HasFactory<TourFactory>
     */
    use HasFactory, HasUuids;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'travel_id',
        'name',
        'starting_date',
        'ending_date',
        'price',
    ];

    public function newEloquentBuilder($query): TourBuilder
    {
        return new TourBuilder($query);
    }

    /**
     * @return BelongsTo<Travel, Tour>
     */
    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starting_date' => 'date',
            'ending_date' => 'date',
        ];
    }

    /**
     * @return Attribute<int, float>
     */
    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
            set: fn ($value): int|float => $value * 100
        );
    }
}
