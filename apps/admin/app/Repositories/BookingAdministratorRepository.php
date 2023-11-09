<?php

declare(strict_types=1);

namespace App\Admin\Repositories;

use App\Admin\Models\Administrator\Administrator;

class BookingAdministratorRepository
{
    public function get(int $bookingId): ?Administrator
    {
        return Administrator::query()
            ->join('administrator_bookings', 'administrators.id', '=', 'administrator_bookings.administrator_id')
            ->where('administrator_bookings.booking_id', $bookingId)
            ->first();
    }
}
