<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Exception\HotelBooking;

use Module\Booking\Deprecated\HotelBooking\Exception\InvalidHotelExceptionInterface;

class NotFoundHotelRoomPrice extends \RuntimeException implements InvalidHotelExceptionInterface
{

}
