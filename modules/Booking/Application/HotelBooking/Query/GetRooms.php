<?php

namespace Module\Booking\Application\HotelBooking\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRooms implements QueryInterface
{
    public function __construct(public readonly int $reservationId) {}
}
