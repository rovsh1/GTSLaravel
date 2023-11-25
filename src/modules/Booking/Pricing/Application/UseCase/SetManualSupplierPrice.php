<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualSupplierPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(int $bookingId, float|int|null $price): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));

        $supplierPrice = new BookingPriceItem(
            $booking->prices()->supplierPrice()->currency(),
            $booking->prices()->supplierPrice()->calculatedValue(),
            $price,
            $booking->prices()->supplierPrice()->penaltyValue(),
        );

        $booking->updatePrice(
            new BookingPrices(
                supplierPrice: $supplierPrice,
                clientPrice: $booking->prices()->clientPrice(),
            )
        );

        $this->bookingRepository->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }
}
