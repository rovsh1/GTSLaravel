<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'order_id',
        'type',
        'status',
        'source',
        'note',
        'creator_id',
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
        'type' => BookingTypeEnum::class,
    ];

    public function scopeWithHotelDetails(Builder $builder)
    {
        $builder
            ->addSelect('bookings.*')
            ->join('orders', 'orders.id', '=', 'bookings.order_id')
            ->join('clients', 'clients.id', '=', 'orders.client_id')
            ->addSelect('clients.name as client_name')
            ->join('booking_hotel_details', 'bookings.id', '=', 'booking_hotel_details.booking_id')
            ->addSelect('booking_hotel_details.date_start as date_start')
            ->addSelect('booking_hotel_details.date_end as date_end')
            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
            ->join('hotels', 'hotels.id', '=', 'booking_hotel_details.hotel_id')
            ->addSelect('hotels.name as hotel_name')
            ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
            ->joinTranslatable('r_cities', 'name as city_name');
    }

}
