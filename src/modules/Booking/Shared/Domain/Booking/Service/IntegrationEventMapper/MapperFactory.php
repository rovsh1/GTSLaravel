<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper;

use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Airport\AirportGuestBindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Airport\AirportGuestUnbindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Hotel\AccommodationGuestBindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Hotel\OrderGuestEditedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Hotel\AccommodationGuestUnbindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Hotel\AccommodationModifiedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer\CarBidAddedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer\CarBidRemovedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer\CarBidModifiedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer\CarBidReplacedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer\TransferGuestBindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer\TransferGuestUnbindedMapper;
use Module\Booking\Shared\Domain\Guest\Event\GuestModified;
use Sdk\Booking\Event\ArrivalDateChanged;
use Sdk\Booking\Event\BookingCancelledEventInterface;
use Sdk\Booking\Event\DepartureDateChanged;
use Sdk\Booking\Event\HotelBooking\AccommodationDetailsEdited;
use Sdk\Booking\Event\HotelBooking\GuestBinded as AccommodationGuestBinded;
use Sdk\Booking\Event\HotelBooking\GuestUnbinded as AccommodationGuestUnbinded;
use Sdk\Booking\Event\PriceUpdated;
use Sdk\Booking\Event\ServiceBooking\GuestBinded as AirportGuestBinded;
use Sdk\Booking\Event\ServiceBooking\GuestUnbinded as AirportGuestUnbinded;
use Sdk\Booking\Event\ServiceDateChanged;
use Sdk\Booking\Event\TransferBooking\CarBidAdded;
use Sdk\Booking\Event\TransferBooking\CarBidDetailsEdited;
use Sdk\Booking\Event\TransferBooking\CarBidRemoved;
use Sdk\Booking\Event\TransferBooking\CarBidReplaced;
use Sdk\Booking\Event\TransferBooking\GuestBinded as TransferGuestBinded;
use Sdk\Booking\Event\TransferBooking\GuestUnbinded as TransferGuestUnbinded;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;

class MapperFactory
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function build(DomainEventInterface $domainEvent): ?MapperInterface
    {
        $mapperClass = $this->getClass($domainEvent::class);

        return $mapperClass ? $this->container->make($mapperClass) : null;
    }

    private function getClass(string $event): ?string
    {
        $class = match ($event) {
            AccommodationGuestBinded::class => AccommodationGuestBindedMapper::class,
            AccommodationGuestUnbinded::class => AccommodationGuestUnbindedMapper::class,
            GuestModified::class => OrderGuestEditedMapper::class,
            AccommodationDetailsEdited::class => AccommodationModifiedMapper::class,

            TransferGuestBinded::class => TransferGuestBindedMapper::class,
            TransferGuestUnbinded::class => TransferGuestUnbindedMapper::class,
            CarBidDetailsEdited::class => CarBidModifiedMapper::class,
            CarBidReplaced::class => CarBidReplacedMapper::class,
            CarBidAdded::class => CarBidAddedMapper::class,
            CarBidRemoved::class => CarBidRemovedMapper::class,

            AirportGuestBinded::class => AirportGuestBindedMapper::class,
            AirportGuestUnbinded::class => AirportGuestUnbindedMapper::class,

            PriceUpdated::class => PriceChangedMapper::class,
            ArrivalDateChanged::class => ArrivalDateChangedMapper::class,
            DepartureDateChanged::class => DepartureDateChangedMapper::class,
            ServiceDateChanged::class => ServiceDateChangedMapper::class,
            default => null
        };

        if ($class !== null) {
            return $class;
        }

        if (is_subclass_of($event, BookingCancelledEventInterface::class)) {
            return BookingCancelledMapper::class;
        }

        return null;
    }
}
