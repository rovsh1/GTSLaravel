<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Shared\Adapter;

use Module\Administrator\Application\UseCase\GetManagerByBookingId;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;

class AdministratorAdapter implements AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): mixed
    {
        return app(GetManagerByBookingId::class)->execute($bookingId);
    }
}