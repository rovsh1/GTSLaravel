<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Shared\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetClientPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, float|int|null $penalty): void
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));
        $clientPrice = new BookingPriceItem(
            $booking->prices()->clientPrice()->currency(),
            $booking->prices()->clientPrice()->calculatedValue(),
            $booking->prices()->clientPrice()->manualValue(),
            $penalty
        );
        $newPrices = new BookingPrices(
            supplierPrice: $booking->prices()->supplierPrice(),
            clientPrice: $clientPrice
        );
        $booking->updatePrice($newPrices);
        $this->bookingUpdater->store($booking);
    }
}
