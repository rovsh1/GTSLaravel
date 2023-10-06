<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\Factory;

use Module\Booking\Application\AirportBooking\Response\BookingDto;
use Module\Booking\Application\AirportBooking\Response\Details\AirportInfoDto;
use Module\Booking\Application\AirportBooking\Response\Details\ServiceInfoDto;
use Module\Booking\Application\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\Shared\Factory\AbstractBookingDtoFactory;
use Module\Booking\Application\Shared\Factory\BookingPriceDtoFactory;
use Module\Booking\Application\Shared\Service\StatusStorage;
use Module\Booking\Domain\AirportBooking\AirportBooking;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestId;

class BookingDtoFactory extends AbstractBookingDtoFactory
{
    public function __construct(
        StatusStorage $statusStorage,
        private readonly BookingPriceDtoFactory $bookingPriceDtoFactory,
    ) {
        parent::__construct($statusStorage);
    }

    public function createFromEntity(BookingInterface $booking): BookingDto
    {
        assert($booking instanceof AirportBooking);

        return new BookingDto(
            id: $booking->id()->value(),
            status: $this->statusStorage->get($booking->status()),
            orderId: $booking->orderId()->value(),
            createdAt: $booking->createdAt(),
            creatorId: $booking->creatorId()->value(),
            note: $booking->note(),
            airportInfo: AirportInfoDto::fromDomain($booking->airportInfo()),
            serviceInfo: ServiceInfoDto::fromDomain($booking->serviceInfo()),
            date: $booking->date(),
            guestIds: $booking->guestIds()->map(fn(GuestId $id) => $id->value()),
            cancelConditions: $booking->cancelConditions() !== null
                ? CancelConditionsDto::fromDomain($booking->cancelConditions())
                : null,
            price: $this->bookingPriceDtoFactory->createFromEntity($booking->price()),
            flightNumber: $booking->additionalInfo()->flightNumber(),
        );
    }
}
