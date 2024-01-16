<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as Query;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Module\Booking\Shared\Infrastructure\Models\Details\Hotel;
use Module\Booking\Shared\Infrastructure\Models\Details\Other;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Enum\SourceEnum;
use Shared\Models\Model;

/**
 * Module\Booking\Transfer\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property StatusEnum $status
 * @property string|null $status_reason
 * @property ServiceTypeEnum $service_type
 * @property float $client_price
 * @property CurrencyEnum $client_currency
 * @property float|null $client_manual_price
 * @property float|null $client_penalty
 * @property float $supplier_price
 * @property CurrencyEnum $supplier_currency
 * @property float|null $supplier_manual_price
 * @property float|null $supplier_penalty
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
        'status_reason',
        'source',
        'creator_id',
        'client_price',
        'client_manual_price',
        'client_currency',
        'client_penalty',
        'supplier_price',
        'supplier_manual_price',
        'supplier_currency',
        'supplier_penalty',
        'cancel_conditions',
        'note',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'service_type' => ServiceTypeEnum::class,
        'source' => SourceEnum::class,
        'client_currency' => CurrencyEnum::class,
        'supplier_currency' => CurrencyEnum::class,
        'cancel_conditions' => 'array',
    ];

    protected static function booted() {}

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

    public function scopeWhereHasGuestId(Builder $builder, int $guestId): void
    {
        $builder->whereExists(function (Query $query) use ($guestId) {
            $query->selectRaw(1)
                ->from('booking_hotel_room_guests')
                ->where('guest_id', $guestId)
                ->whereRaw(
                    'EXISTS(SELECT 1 FROM booking_hotel_accommodations WHERE bookings.id = booking_hotel_accommodations.booking_id AND booking_hotel_room_guests.accommodation_id = booking_hotel_accommodations.id)'
                );
        })
            ->orWhereExists(function (Query $query) use ($guestId) {
                $query->selectRaw(1)
                    ->from('booking_car_bid_guests')
                    ->where('guest_id', $guestId)
                    ->whereRaw(
                        'EXISTS(SELECT 1 FROM booking_car_bids WHERE bookings.id = booking_car_bids.booking_id AND booking_car_bid_guests.car_bid_id = booking_car_bids.id)'
                    );
            })
            ->orWhereExists(function (Query $query) use ($guestId) {
                $query->selectRaw(1)
                    ->from('booking_airport_guests')
                    ->where('guest_id', $guestId)
                    ->whereColumn('booking_airport_guests.booking_id','bookings.id');
            });
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
