<?php

namespace GTS\Services\Traveline\Infrastructure\Api\Hotel\Booking;

use Carbon\CarbonInterface;

interface ApiInterface
{

    public function getBookings(?int $bookingId = null, ?int $hotelId = null, ?CarbonInterface $startDate = null);

}
