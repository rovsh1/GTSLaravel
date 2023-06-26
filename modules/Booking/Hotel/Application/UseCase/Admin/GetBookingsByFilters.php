<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingsByFilters implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function execute(array $filters = []): mixed
    {
        return $this->repository->get();
    }
}
