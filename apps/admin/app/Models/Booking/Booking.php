<?php

declare(strict_types=1);

namespace App\Admin\Models\Booking;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;

class Booking extends \Module\Booking\Infrastructure\ServiceBooking\Models\Booking
{
    public function scopeWithoutHotelBooking(Builder $builder): void
    {
        $builder->whereNotExists(function (Query $query) {
            $query->selectRaw(1)
                ->from('booking_hotel_details')
                ->whereColumn($this->table . '.id', '=', 'booking_hotel_details.booking_id');
        });
    }

    public function scopeWhereCityId(Builder $builder, int $cityId): void
    {
        $builder->where('hotels.city_id', $cityId);
    }
}
