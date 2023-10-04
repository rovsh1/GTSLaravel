<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin\Price;

use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Infrastructure\HotelBooking\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetNetPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, float|int|null $penalty): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setNetPenalty($penalty === null ? null : (float)$penalty);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}