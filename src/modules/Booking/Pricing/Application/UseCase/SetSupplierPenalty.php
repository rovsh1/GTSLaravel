<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Moderation\Domain\Booking\Service\BookingUpdater;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
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
        $this->bookingUpdater->store($booking);
    }
}
