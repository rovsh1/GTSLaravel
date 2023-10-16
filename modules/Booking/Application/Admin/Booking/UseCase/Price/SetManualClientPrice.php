<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase\Price;

use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualClientPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, float|int|null $price): void
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));
        $clientPrice = new BookingPriceItem(
            $booking->prices()->clientPrice()->currency(),
            $booking->prices()->clientPrice()->calculatedValue(),
            $price,
            $booking->prices()->clientPrice()->penaltyValue(),
        );
        $newPrices = new BookingPrices(
            supplierPrice: $booking->prices()->supplierPrice(),
            clientPrice: $clientPrice
        );
        $booking->updatePrice($newPrices);
        $this->bookingUpdater->store($booking);
    }
}
