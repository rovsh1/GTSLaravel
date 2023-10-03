<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Booking\Infrastructure\Shared\Models\Booking as BaseModel;

/**
 * Module\Booking\Transfer\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property-read int[] $guest_ids
 */
class Booking extends BaseModel
{
    protected $attributes = [
        'type' => BookingTypeEnum::TRANSFER,
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->whereType(BookingTypeEnum::TRANSFER);
        });
    }

    public function scopeWhereType(Builder $builder, BookingTypeEnum $type): void
    {
        $builder->where($this->getTable() . '.type', $type);
    }

    public function scopeWithDetails(Builder $builder): void
    {
//        $builder->addSelect('bookings.*')
//            ->join('orders', 'orders.id', '=', 'bookings.order_id')
//            ->join('clients', 'clients.id', '=', 'orders.client_id')
//            ->addSelect('clients.name as client_name')
//            ->join('booking_hotel_details', 'bookings.id', '=', 'booking_hotel_details.booking_id')
//            ->addSelect('booking_hotel_details.date_start as date_start')
//            ->addSelect('booking_hotel_details.date_end as date_end')
//            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
//            ->join('hotels', 'hotels.id', '=', 'booking_hotel_details.hotel_id')
//            ->addSelect('hotels.name as hotel_name')
//            ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
//            ->joinTranslatable('r_cities', 'name as city_name');
    }
}
