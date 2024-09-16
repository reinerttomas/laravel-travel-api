<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TravelFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

final class Travel extends Model
{
    /**
     * @phpstan-use HasFactory<TravelFactory>
     */
    use HasFactory, HasSlug, HasUuids;

    protected $table = 'travels';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_public',
        'number_of_days',
    ];

    /**
     * @return HasMany<Tour>
     */
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * @return Attribute<int, never>
     */
    public function numberOfNights(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value, array $attributes) => $attributes['number_of_days'] - 1,
        );
    }
}
