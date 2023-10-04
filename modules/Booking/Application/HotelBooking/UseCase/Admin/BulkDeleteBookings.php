<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class BulkDeleteBookings implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    /**
     * @param int[] $ids
     * @return Builder
     */
    public function execute(array $ids): void
    {
        $this->repository->bulkDelete($ids);
    }
}