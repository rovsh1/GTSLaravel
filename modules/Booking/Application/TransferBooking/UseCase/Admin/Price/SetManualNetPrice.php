<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\UseCase\Admin\Price;

use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualNetPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, float $price): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setNetPriceManually($price);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
