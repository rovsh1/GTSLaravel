<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;

interface AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): ?AdministratorDto;
}
