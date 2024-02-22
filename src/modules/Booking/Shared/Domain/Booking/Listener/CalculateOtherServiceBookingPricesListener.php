<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\Shared\Domain\Booking\Adapter\PricingAdapterInterface;
use Sdk\Booking\Event\BookingCreated;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

class CalculateOtherServiceBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly PricingAdapterInterface $pricingAdapter
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof BookingCreated);

        if ($event->booking->serviceType() !== ServiceTypeEnum::OTHER_SERVICE) {
            return;
        }

        $this->pricingAdapter->recalculate($event->bookingId()->value());
    }
}
