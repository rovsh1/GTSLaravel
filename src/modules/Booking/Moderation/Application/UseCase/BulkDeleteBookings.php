<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class BulkDeleteBookings implements UseCaseInterface
{
    public function __construct(
        private readonly BookingDbContextInterface $bookingDbContext
    ) {}

    /**
     * @param int[] $ids
     * @return void
     */
    public function execute(array $ids): void
    {
        $this->bookingDbContext->bulkDelete($ids);
    }
}
