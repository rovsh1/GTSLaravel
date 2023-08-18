<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Listener;

use Module\Booking\Common\Domain\Event\BookingDeleted;
use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaProcessingMethodFactory;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingQuotaUpdaterListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert(
            $event instanceof BookingStatusEventInterface || $event instanceof BookingDeleted
        );

        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($event->booking()->quotaProcessingMethod());
        $quotaProcessingMethod->process($event->booking());
    }
}
