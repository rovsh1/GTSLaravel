<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Infrastructure\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;
use Module\Administrator\Application\UseCase\GetManagerByBookingId;
use Module\Booking\Requesting\Domain\Adapter\AdministratorAdapterInterface;

class AdministratorAdapter implements AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): ?AdministratorDto
    {
        return app(GetManagerByBookingId::class)->execute($bookingId);
    }
}
