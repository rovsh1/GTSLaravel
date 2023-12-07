<?php

namespace Sdk\Booking\Event\ServiceBooking;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Shared\Event\IntegrationEventMessages;

class GuestUnbinded extends AbstractDetailsEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        DetailsInterface $details,
        public readonly GuestId $guestId,
    ) {
        parent::__construct($details);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::AIRPORT_BOOKING_GUEST_REMOVED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'guestId' => $this->guestId->value()
        ];
    }
}
