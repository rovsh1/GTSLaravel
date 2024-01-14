<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationAdded;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationDeleted;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationModified;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationReplaced;
use Sdk\Booking\IntegrationEvent\HotelBooking\GuestAdded;
use Sdk\Booking\IntegrationEvent\HotelBooking\GuestRemoved;
use Sdk\Booking\IntegrationEvent\HotelBooking\PeriodChanged;
use Sdk\Module\Contracts\Support\ContainerInterface;

class RegistrarFactory
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function build(BookingEventInterface $event): ?RegistrarInterface
    {
        $class = $this->getClass($event::class);

        return $class ? $this->container->make($class) : null;
    }

    private function getClass(string $event): ?string
    {
        return match ($event) {
            PeriodChanged::class => PeriodChangedRegistrar::class,
            AccommodationAdded::class => AccommodationAddedRegistrar::class,
            AccommodationModified::class => AccommodationModifiedRegistrar::class,
            AccommodationDeleted::class => AccommodationRemovedRegistrar::class,
            AccommodationReplaced::class => AccommodationReplacedRegistrar::class,
            GuestAdded::class => GuestAddedRegistrar::class,
            GuestRemoved::class => GuestRemovedRegistrar::class,
            default => null
        };
    }
}