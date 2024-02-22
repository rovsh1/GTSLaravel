<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Support\Adapter;

use Illuminate\Support\Facades\DB;
use Pkg\Booking\Requesting\Domain\Adapter\HotelAdapterInterface;

class HotelAdapter implements HotelAdapterInterface
{
    public function getAdministratorEmails(int $hotelId): ?array
    {
        $contacts = DB::table('hotel_administrators')
            ->select('email')
            ->where('hotel_id', $hotelId)
            ->where('status', true)
            ->whereNull('deleted_at')
            ->get();

        return $contacts->pluck('email')->toArray();
    }
}
