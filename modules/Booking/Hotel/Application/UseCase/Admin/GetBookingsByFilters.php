<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;

class GetBookingsByFilters
{
    public function __construct(
        private readonly BookingRepository $repository
    ) {}

    public function execute(array $filters = []): mixed
    {
        return $this->repository->get();
    }
}
