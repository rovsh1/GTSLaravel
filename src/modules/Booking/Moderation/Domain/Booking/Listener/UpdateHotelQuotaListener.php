<?php

namespace Module\Booking\Moderation\Domain\Booking\Listener;

use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\QuotaProcessingMethodFactory;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class UpdateHotelQuotaListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly HotelBookingRepositoryInterface $detailsRepository,
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory
    ) {
    }

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof QuotaChangedEventInterface);

        $booking = $event->booking();
        $details = $this->detailsRepository->findOrFail($booking->id());

        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($details->quotaProcessingMethod());
        $quotaProcessingMethod->process($booking, $details);
    }
}
