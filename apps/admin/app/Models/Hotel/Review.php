<?php

namespace App\Admin\Models\Hotel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Hotel\ReviewStatusEnum;

/**
 * App\Admin\Models\Hotel\Review
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $booking_id
 * @property string $name
 * @property string $text
 * @property float $rating
 * @property ReviewStatusEnum $status
 * @method static Builder|Rule newModelQuery()
 * @method static Builder|Rule newQuery()
 * @method static Builder|Rule query()
 * @method static Builder|Rule whereHotelId($value)
 * @method static Builder|Rule whereId($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    use HasQuicksearch;

    protected $table = 'hotel_reviews';

    protected array $quicksearch = ['id', 'name%',];

    protected $fillable = [
        'hotel_id',
        'name',
        'text',
        'booking_id',
        'rating',
        'status',
    ];

    protected $casts = [
        'status' => ReviewStatusEnum::class,
    ];

    public function ratings(): HasMany
    {
        return $this->hasMany(ReviewRating::class, 'review_id');
    }

    public function __toString()
    {
        return $this->name;
    }
}
