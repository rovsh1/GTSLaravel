<?php

declare(strict_types=1);

namespace App\Admin\Models\Booking;

use Illuminate\Database\Eloquent\Builder;

class Booking extends \Module\Booking\Infrastructure\ServiceBooking\Models\Booking
{
    public function scopeWithoutHotelBooking(Builder $builder): void
    {
        $builder->whereNotExists(function (\Illuminate\Database\Query\Builder $query) {
            $query->selectRaw(1)
                ->from('booking_hotel_details')
                ->whereColumn($this->table . '.id', '=', 'booking_hotel_details.booking_id');
        });
    }
}
