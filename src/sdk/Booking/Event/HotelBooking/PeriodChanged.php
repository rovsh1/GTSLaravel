<?php

declare(strict_types=1);

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Dto\PeriodDto;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Event\BookingDateChangedEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\PeriodChanged as IntegrationEvent;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\HotelBooking\BookingPeriod;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class PeriodChanged extends AbstractDetailsEvent implements PriceBecomeDeprecatedEventInterface,
                                                            BookingDateChangedEventInterface,
                                                            HasIntegrationEventInterface
{
    public function __construct(
        DetailsInterface $details,
        private readonly BookingPeriod $periodBefore,
    ) {
        parent::__construct($details);
    }

    public function integrationEvent(): IntegrationEventInterface
    {
        assert($this->details instanceof HotelBooking);

        return new IntegrationEvent(
            $this->details->bookingId()->value(),
            new PeriodDto($this->periodBefore->dateFrom(), $this->periodBefore->dateTo()),
            new PeriodDto($this->details->bookingPeriod()->dateFrom(), $this->details->bookingPeriod()->dateTo()),
        );
    }
}
