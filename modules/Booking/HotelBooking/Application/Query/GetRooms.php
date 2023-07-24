<?php

namespace Module\Booking\HotelBooking\Application\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRooms implements QueryInterface
{
    public function __construct(public readonly int $reservationId) {}
}
