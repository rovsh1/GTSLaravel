<?php

declare(strict_types=1);

namespace Module\Booking\Application\ServiceBooking\UseCase\Admin\Price;

use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetGrossPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, float|int|null $penalty): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setGrossPenalty($penalty === null ? null : (float)$penalty);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
