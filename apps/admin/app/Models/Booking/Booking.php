<?php

declare(strict_types=1);

namespace App\Admin\Models\Booking;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('bookings.*')
                ->join('r_cities', 'r_cities.id', '=', 'bookings.city_id')
                ->joinTranslatable('r_cities', 'name as city_name')
                ->join('clients', 'clients.id', '=', 'bookings.client_id')
                ->addSelect('clients.name as client_name')
                ->join('booking_hotel_details', 'booking_hotel_details.booking_id', '=', 'bookings.id')
                ->addSelect('booking_hotel_details.date_start as date_start')
                ->addSelect('booking_hotel_details.date_end as date_end');
        });
    }
}
