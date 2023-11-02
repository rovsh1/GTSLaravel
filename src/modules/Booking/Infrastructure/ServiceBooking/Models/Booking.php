<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\Enum\SourceEnum;
use Module\Shared\Infrastructure\Models\Model;

/**
 * Module\Booking\Transfer\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property-read Details\Airport|null $airportDetails
 * @property-read Details\Transfer|null $transferDetails
 * @property-read Details\Other|null $otherDetails
 * @property-read Details\Hotel|null $hotelDetails
 */
class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';

    protected $fillable = [
        'order_id',
        'service_type',
        'status',
        'source',
        'creator_id',
        'prices',
        'cancel_conditions',
        'note',
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
        'service_type' => ServiceTypeEnum::class,
        'source' => SourceEnum::class,
        'prices' => 'array',
        'cancel_conditions' => 'array',
    ];

    public function scopeApplyCriteria(Builder $query, array $criteria): void
    {
        if (isset($criteria['quicksearch'])) {
            $query->quicksearch($criteria['quicksearch']);
            unset($criteria['quicksearch']);
        }

        foreach ($criteria as $k => $v) {
            $scopeName = \Str::camel($k);
            $scopeMethod = 'where' . ucfirst($scopeName);
            $hasScope = $this->hasNamedScope($scopeMethod);
            if ($hasScope) {
                $query->$scopeMethod($v);
                continue;
            }
            $query->where($k, $v);
        }
    }

    protected static function booted() {}

    public function airportDetails(): HasOne
    {
        return $this->hasOne(Details\Airport::class, 'booking_id');
    }

    public function transferDetails(): HasOne
    {
        return $this->hasOne(Details\Transfer::class, 'booking_id');
    }

    public function otherDetails(): HasOne
    {
        return $this->hasOne(Details\Other::class, 'booking_id');
    }

    public function hotelDetails(): HasOne
    {
        return $this->hasOne(Details\Hotel::class, 'booking_id');
    }
}
