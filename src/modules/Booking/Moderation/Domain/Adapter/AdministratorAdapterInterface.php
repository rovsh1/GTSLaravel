<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;

interface AdministratorAdapterInterface
{
    public function getBookingAdministrator(BookingId $bookingId): ?AdministratorDto;

    public function setBookingAdministrator(BookingId $bookingId, int $administratorId): void;

    public function getOrderAdministrator(OrderId $orderId): ?AdministratorDto;

    public function setOrderAdministrator(OrderId $orderId, int $administratorId): void;
}
