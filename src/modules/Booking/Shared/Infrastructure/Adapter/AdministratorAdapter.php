<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;
use Module\Administrator\Application\UseCase\GetManagerByBookingId;
use Module\Administrator\Application\UseCase\GetManagerByOrderId;
use Module\Administrator\Application\UseCase\SetBookingAdministrator;
use Module\Administrator\Application\UseCase\SetOrderAdministrator;
use Module\Booking\Shared\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;

class AdministratorAdapter implements AdministratorAdapterInterface
{
    public function getBookingAdministrator(BookingId $bookingId): ?AdministratorDto
    {
        return app(GetManagerByBookingId::class)->execute($bookingId->value());
    }

    public function setBookingAdministrator(BookingId $bookingId, int $administratorId): void
    {
        app(SetBookingAdministrator::class)->execute($bookingId->value(), $administratorId);
    }

    public function getOrderAdministrator(OrderId $orderId): ?AdministratorDto
    {
        return app(GetManagerByOrderId::class)->execute($orderId->value());
    }

    public function setOrderAdministrator(OrderId $orderId, int $administratorId): void
    {
        app(SetOrderAdministrator::class)->execute($orderId->value(), $administratorId);
    }
}
