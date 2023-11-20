<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Module\Booking\Shared\Infrastructure\Models\Details\Hotel;
use Module\Booking\Shared\Infrastructure\Models\Details\Other;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\Enum\SourceEnum;
use Module\Shared\Infrastructure\Models\Model;

/**
 * Module\Booking\Transfer\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property ServiceTypeEnum $service_type
 * @property-read Hotel|Airport|Transfer|Other|null $details
 * @property-read Airport|null $airportDetails
 * @property-read Transfer|null $transferDetails
 * @property-read Other|null $otherDetails
 * @property-read Hotel|null $hotelDetails
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

    protected static function booted()
    {
    }

    public function details(): HasOne
    {
        return match ($this->service_type) {
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER,
            ServiceTypeEnum::DAY_CAR_TRIP,
            ServiceTypeEnum::INTERCITY_TRANSFER,
            ServiceTypeEnum::TRANSFER_TO_RAILWAY,
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY,
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT,
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->transferDetails(),
            ServiceTypeEnum::CIP_MEETING_IN_AIRPORT,
            ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT => $this->airportDetails(),
            ServiceTypeEnum::HOTEL_BOOKING => $this->hotelDetails(),
            ServiceTypeEnum::OTHER_SERVICE => $this->otherDetails(),
        };
    }

    private function airportDetails(): HasOne
    {
        return $this->hasOne(Airport::class, 'booking_id');
    }

    private function transferDetails(): HasOne
    {
        return $this->hasOne(Transfer::class, 'booking_id');
    }

    private function otherDetails(): HasOne
    {
        return $this->hasOne(Other::class, 'booking_id');
    }

    private function hotelDetails(): HasOne
    {
        return $this->hasOne(Hotel::class, 'booking_id');
    }
}
