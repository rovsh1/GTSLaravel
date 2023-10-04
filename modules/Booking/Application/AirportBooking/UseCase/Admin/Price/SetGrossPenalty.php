<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin\Price;

use Module\Booking\Airport\Infrastructure\Repository\BookingRepository;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetGrossPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, float|int|null $penalty): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setGrossPenalty($penalty === null ? null : (float)$penalty);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
