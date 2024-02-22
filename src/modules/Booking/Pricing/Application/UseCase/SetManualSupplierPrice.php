<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualSupplierPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {}

    public function execute(int $bookingId, float|int|null $price): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));

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

        $this->bookingUnitOfWork->commit();
    }
}
