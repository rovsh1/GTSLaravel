<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Listener\CalculateOtherServiceBookingPricesListener;
use Module\Booking\Shared\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateBookingCancelConditionsListener;
use Module\Booking\Shared\Domain\Booking\Listener\UpdateHotelQuotaListener;
use Module\Booking\Shared\Domain\Booking\Service\EventMapper\AccommodationGuestBindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\EventMapper\AccommodationGuestUnbindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\EventMapper\AirportGuestBindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\EventMapper\AirportGuestUnbindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\EventMapper\TransferGuestBindedMapper;
use Module\Booking\Shared\Domain\Booking\Service\EventMapper\TransferGuestUnbindedMapper;
use Module\Booking\Shared\Domain\Order\Event\OrderCancelled;
use Module\Booking\Shared\Domain\Order\Listener\OrderCancelledListener;
use Sdk\Booking\Contracts\Event\CarBidEventInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\QuotaChangedEventInterface;
use Sdk\Booking\Event\BookingCreated;
use Sdk\Booking\Event\HotelBooking\GuestBinded as AccommodationGuestBinded;
use Sdk\Booking\Event\HotelBooking\GuestUnbinded as AccommodationGuestUnbinded;
use Sdk\Booking\Event\ServiceBooking\GuestBinded as AirportGuestBinded;
use Sdk\Booking\Event\ServiceBooking\GuestUnbinded as AirportGuestUnbinded;
use Sdk\Booking\Event\TransferBooking\GuestBinded as TransferGuestBinded;
use Sdk\Booking\Event\TransferBooking\GuestUnbinded as TransferGuestUnbinded;
use Sdk\Shared\Support\ServiceProvider\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingCreated::class => CalculateOtherServiceBookingPricesListener::class,

        OrderCancelled::class => OrderCancelledListener::class,

        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
        QuotaChangedEventInterface::class => UpdateHotelQuotaListener::class,

        CarBidEventInterface::class => UpdateBookingCancelConditionsListener::class,
    ];

    protected array $integrationEventMappers = [
        AccommodationGuestBinded::class => AccommodationGuestBindedMapper::class,
        AccommodationGuestUnbinded::class => AccommodationGuestUnbindedMapper::class,
        TransferGuestBinded::class => TransferGuestBindedMapper::class,
        TransferGuestUnbinded::class => TransferGuestUnbindedMapper::class,
        AirportGuestBinded::class => AirportGuestBindedMapper::class,
        AirportGuestUnbinded::class => AirportGuestUnbindedMapper::class,
    ];
}
