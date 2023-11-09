<?php

declare(strict_types=1);

namespace Module\Administrator\Domain\Repository;

use Module\Administrator\Domain\ValueObject\AdministratorId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;

interface BookingAdministratorRepositoryInterface
{
    public function set(BookingId $bookingId, AdministratorId $administratorId): void;
}
