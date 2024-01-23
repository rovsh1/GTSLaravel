<?php

declare(strict_types=1);

namespace Pkg\Hotel\Administration\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetBookingNoCheckIn implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {}

    public function execute(int $bookingId, ?float $supplierPenaltyAmount = null): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));

        $this->bookingUnitOfWork->persist($booking);
        if (!empty($supplierPenaltyAmount)) {
            $booking->toCancelledFee($supplierPenaltyAmount);
        } else {
            $booking->toCancelledNoFee();
        }

        $this->bookingUnitOfWork->commit();
    }
}
