<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Adapter;

interface AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): mixed;
}
