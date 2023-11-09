<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Adapter;

use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;

interface AdministratorAdapterInterface
{
    public function setBookingAdministrator(BookingId $bookingId, int $administratorId): void;

    public function setOrderAdministrator(OrderId $orderId, int $administratorId): void;
}
