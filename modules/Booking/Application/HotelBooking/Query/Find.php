<?php

namespace Module\Booking\Application\HotelBooking\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class Find implements QueryInterface
{
    public function __construct(public readonly int $id) {}
}