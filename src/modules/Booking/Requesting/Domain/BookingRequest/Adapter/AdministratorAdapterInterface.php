<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\BookingRequest\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;

interface AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): ?AdministratorDto;
}
