<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetClientPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {}

    public function execute(int $bookingId, float|int|null $penalty): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));

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

        $this->bookingUnitOfWork->commit();
    }
}
