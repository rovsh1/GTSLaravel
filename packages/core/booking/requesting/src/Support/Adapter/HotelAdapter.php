<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Support\Adapter;

use Illuminate\Support\Facades\DB;
use Pkg\Booking\Requesting\Domain\Adapter\HotelAdapterInterface;
use Sdk\Shared\Enum\ContactTypeEnum;

class HotelAdapter implements HotelAdapterInterface
{
    public function getEmail(int $hotelId): ?string
    {
        $contact = DB::table('hotel_contacts')
            ->where('hotel_id', $hotelId)
            ->where('type', ContactTypeEnum::EMAIL)
            ->where('is_main', true)->first();

        return $contact?->value;
    }
}
