<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\Moderation\Domain\Booking\Factory\TransferCancelConditionsFactory;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class UpdateBookingCancelConditionsListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly TransferCancelConditionsFactory $cancelConditionsFactory,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof CarBidChangedInterface);

        $booking = $this->bookingUnitOfWork->findOrFail($event->bookingId());
        $details = $this->bookingUnitOfWork->getDetails($event->bookingId());
        if (!$details instanceof TransferDetailsInterface) {
            return;
        }
        $carBids = $this->carBidDbContext->getByBookingId($event->bookingId());
        $cancelConditions = $this->cancelConditionsFactory->build(
            $details->serviceInfo()->id(),
            $carBids,
            $details->serviceDate()
        );
        $booking->setCancelConditions($cancelConditions);

        $this->bookingUnitOfWork->persist($booking);
        $this->bookingUnitOfWork->commit();
    }
}
