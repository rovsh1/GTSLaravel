<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Listener;

use Module\Booking\Common\Domain\Event\BookingDeleted;
use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaManager;
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
        if (!$event->booking() instanceof Booking) {
            //hack процессим только отельные брони
            return;
        }

        $this->quotaManager->process($event->booking());
    }
}
