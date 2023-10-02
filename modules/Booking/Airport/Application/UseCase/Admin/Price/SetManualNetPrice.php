<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin\Price;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Airport\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualNetPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, float $price): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setNetPriceManually($price);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
