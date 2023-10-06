<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\UseCase\Admin;

use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class BulkDeleteBookings implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    /**
     * @param int[] $ids
     * @return void
     */
    public function execute(array $ids): void
    {
        $this->repository->bulkDelete($ids);
    }
}
