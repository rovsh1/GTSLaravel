<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingsByFilters implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository
    ) {}

    public function execute(array $filters = []): mixed
    {
        return $this->repository->get();
    }
}
