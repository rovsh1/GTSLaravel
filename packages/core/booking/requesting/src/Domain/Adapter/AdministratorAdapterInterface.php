<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Adapter;

use Module\Administrator\Application\Response\AdministratorDto;

interface AdministratorAdapterInterface
{
    public function getManagerByBookingId(int $bookingId): ?AdministratorDto;
}