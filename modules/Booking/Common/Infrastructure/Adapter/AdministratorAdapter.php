<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Adapter;

use Module\Administrator\Application\UseCase\GetManagerByBookingId;
use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;

class AdministratorAdapter implements AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): mixed
    {
        return app(GetManagerByBookingId::class)->execute($bookingId);
    }
}
