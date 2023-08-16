<?php

namespace Module\Booking\HotelBooking\Domain\Listener;

use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;
use Module\Booking\HotelBooking\Domain\Event\QuotaAffectEventInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaProcessingMethodFactory;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class UpdateHotelQuotasListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory,
    ) {
    }

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof QuotaAffectEventInterface || $event instanceof BookingStatusEventInterface);

        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($event->booking()->quotaProcessingMethod());
        $quotaProcessingMethod->process($event->booking()->id());
    }
}
