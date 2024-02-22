<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Pricing\Application\Exception\SupplierPenaltyCannotBeZero;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetSupplierPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {}

    public function execute(int $bookingId, float|int|null $penalty): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));

        if ($booking->status()->value() === StatusEnum::CANCELLED_FEE && empty($penalty)) {
            throw new SupplierPenaltyCannotBeZero();
        }

        $supplierPrice = new BookingPriceItem(
            $booking->prices()->supplierPrice()->currency(),
            $booking->prices()->supplierPrice()->calculatedValue(),
            $booking->prices()->supplierPrice()->manualValue(),
            $penalty
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
