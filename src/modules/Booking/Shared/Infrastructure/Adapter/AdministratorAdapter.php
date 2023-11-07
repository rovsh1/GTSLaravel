<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;
use Module\Administrator\Application\UseCase\GetManagerByBookingId;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;

class AdministratorAdapter implements AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): ?AdministratorDto
    {
        return app(GetManagerByBookingId::class)->execute($bookingId);
    }
}
