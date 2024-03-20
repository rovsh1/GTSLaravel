<?php

declare(strict_types=1);

namespace Sdk\Booking\Event;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\IntegrationEvent\DetailsFieldModified;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

class DetailsFieldUpdated extends AbstractDetailsEvent implements HasIntegrationEventInterface
{
    public function __construct(
        DetailsInterface $details,
        public readonly string $field,
        public readonly mixed $value,
        public readonly mixed $valueBefore,
    ) {
        parent::__construct($details);
    }

    public function bookingId(): BookingId
    {
        return $this->details->bookingId();
    }

    public function integrationEvent(): IntegrationEventInterface
    {
        return new DetailsFieldModified(
            $this->bookingId()->value(),
            $this->field,
            $this->value,
            $this->valueBefore
        );
    }
}
