<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper;

use Sdk\Booking\Event\HotelBooking\GuestBinded as AccommodationGuestBinded;
use Sdk\Booking\Event\HotelBooking\GuestUnbinded as AccommodationGuestUnbinded;
use Sdk\Booking\Event\PriceUpdated;
use Sdk\Booking\Event\ServiceBooking\GuestBinded as AirportGuestBinded;
use Sdk\Booking\Event\ServiceBooking\GuestUnbinded as AirportGuestUnbinded;
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
        return match ($event) {
            AccommodationGuestBinded::class => AccommodationGuestBindedMapper::class,
            AccommodationGuestUnbinded::class => AccommodationGuestUnbindedMapper::class,
            TransferGuestBinded::class => TransferGuestBindedMapper::class,
            TransferGuestUnbinded::class => TransferGuestUnbindedMapper::class,
            AirportGuestBinded::class => AirportGuestBindedMapper::class,
            AirportGuestUnbinded::class => AirportGuestUnbindedMapper::class,
            PriceUpdated::class => PriceChangedMapper::class,
            default => null
        };
    }
}