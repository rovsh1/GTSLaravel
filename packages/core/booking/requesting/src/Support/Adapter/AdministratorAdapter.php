<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Support\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;
use Module\Administrator\Application\UseCase\GetManagerByBookingId;
use Pkg\Booking\Requesting\Domain\Adapter\AdministratorAdapterInterface;

class AdministratorAdapter implements AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): ?AdministratorDto
    {
        return app(GetManagerByBookingId::class)->execute($bookingId);
    }
}
