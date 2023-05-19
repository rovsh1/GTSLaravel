<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Booking\Booking;
use App\Admin\Support\Repository\RepositoryInterface;

class HotelBookingRepository implements RepositoryInterface
{
    public function queryWithCriteria(array $criteria = [])
    {
        return Booking::query();
    }
}
