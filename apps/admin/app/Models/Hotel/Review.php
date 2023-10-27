<?php

namespace App\Admin\Models\Hotel;

use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\Hotel\ReviewStatusEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

/**
 * App\Admin\Models\Hotel\Review
 *
 * @property int $id
 * @property int $hotel_id
 * @property string $name
 * @property string $text
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
        'reservation_id',
        'rating',
        'status',
    ];

    protected $casts = [
        'status' => ReviewStatusEnum::class
    ];

    public function __toString()
    {
        return $this->name;
    }
}
