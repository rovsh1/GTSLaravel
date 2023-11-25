<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\OrderId;

interface AdministratorAdapterInterface
{
    public function getBookingAdministrator(BookingId $bookingId): ?AdministratorDto;

    public function setBookingAdministrator(BookingId $bookingId, int $administratorId): void;

    public function getOrderAdministrator(OrderId $orderId): ?AdministratorDto;

    public function setOrderAdministrator(OrderId $orderId, int $administratorId): void;
}
