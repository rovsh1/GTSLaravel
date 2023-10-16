<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase\Price;

use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetSupplierPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, float|int|null $penalty): void
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));
        $supplierPrice = new BookingPriceItem(
            $booking->prices()->supplierPrice()->currency(),
            $booking->prices()->supplierPrice()->calculatedValue(),
            $booking->prices()->supplierPrice()->manualValue(),
            $penalty
        );
        $newPrices = new BookingPrices(
            supplierPrice: $supplierPrice,
            clientPrice: $booking->prices()->clientPrice(),
        );
        $booking->updatePrice($newPrices);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
