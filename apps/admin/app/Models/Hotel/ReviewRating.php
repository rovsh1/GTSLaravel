<?php

namespace App\Admin\Models\Hotel;

use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\Hotel\ReviewRatingTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * App\Admin\Models\Hotel\ReviewRating
 *
 * @property int $id
 * @property int $review_id
 * @property ReviewRatingTypeEnum $type
 * @property float $value
 * @method static Builder|Rule newModelQuery()
 * @method static Builder|Rule newQuery()
 * @method static Builder|Rule query()
 * @method static Builder|Rule whereId($value)
 * @mixin \Eloquent
 */
class ReviewRating extends Model
{
    protected $table = 'hotel_review_ratings';

    protected $fillable = [
        'review_id',
        'type',
        'value',
    ];

    protected $casts = [
        'type' => ReviewRatingTypeEnum::class,
    ];
}
