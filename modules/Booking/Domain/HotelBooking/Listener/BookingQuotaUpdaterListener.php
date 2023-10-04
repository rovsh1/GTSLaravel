<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Listener;

use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\QuotaManager;
use Module\Booking\Domain\Shared\Event\BookingDeleted;
use Module\Booking\Domain\Shared\Event\BookingStatusEventInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingQuotaUpdaterListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly QuotaManager $quotaManager
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert(
            $event instanceof BookingStatusEventInterface || $event instanceof BookingDeleted
        );
        if (!$event->booking() instanceof HotelBooking) {
            //hack процессим только отельные брони
            return;
        }

        $this->quotaManager->process($event->booking());
    }
}
