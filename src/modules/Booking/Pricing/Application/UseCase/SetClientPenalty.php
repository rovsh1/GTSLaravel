<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetClientPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(int $bookingId, float|int|null $penalty): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));

        $clientPrice = new BookingPriceItem(
            $booking->prices()->clientPrice()->currency(),
            $booking->prices()->clientPrice()->calculatedValue(),
            $booking->prices()->clientPrice()->manualValue(),
            $penalty
        );

        $booking->updatePrice(
            new BookingPrices(
                supplierPrice: $booking->prices()->supplierPrice(),
                clientPrice: $clientPrice
            )
        );

        $this->bookingRepository->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }
}
