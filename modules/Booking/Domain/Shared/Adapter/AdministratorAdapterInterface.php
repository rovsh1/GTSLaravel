<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Adapter;

interface AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): mixed;
}
