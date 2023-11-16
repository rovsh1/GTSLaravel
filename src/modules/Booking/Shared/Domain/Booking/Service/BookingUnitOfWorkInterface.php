<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;

interface BookingUnitOfWorkInterface
{
    public function bookingRepository(): BookingRepositoryInterface;

    public function detailsRepository(): DetailsRepositoryInterface;

    public function commit(): void;
}
