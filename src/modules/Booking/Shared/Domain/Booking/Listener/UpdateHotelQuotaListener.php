<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\Moderation\Application\Exception\HotelDetailsExpectedException;
use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\QuotaProcessingMethodFactory;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class UpdateHotelQuotaListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory
    ) {
    }

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof QuotaChangedEventInterface);

        $booking = $event->booking();
        $details = $this->detailsRepository->findOrFail($booking->id());
        if (!$details instanceof HotelBooking) {
            throw new HotelDetailsExpectedException();
        }

        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($details->quotaProcessingMethod());
        $quotaProcessingMethod->process($booking, $details);
    }
}
