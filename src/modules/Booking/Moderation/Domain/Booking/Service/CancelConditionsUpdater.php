<?php

namespace Module\Booking\Moderation\Domain\Booking\Service;

use Module\Booking\Moderation\Domain\Booking\Factory\TransferCancelConditionsFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;

class CancelConditionsUpdater
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly TransferCancelConditionsFactory $cancelConditionsFactory,
    ) {}

    public function update(Booking $booking): void
    {
        $details = $this->detailsRepository->findOrFail($booking->id());
        assert($details instanceof TransferDetailsInterface);
        $cancelConditions = $this->cancelConditionsFactory->build(
            $details->serviceInfo()->id(),
            $details->carBids(),
            $details->serviceDate()
        );
        $booking->setCancelConditions($cancelConditions);
        $this->bookingRepository->store($booking);
    }
}