<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Factory;

use Module\Booking\Airport\Application\Dto\BookingDto;
use Module\Booking\Airport\Application\Dto\Details\AirportInfoDto;
use Module\Booking\Airport\Application\Dto\Details\ServiceInfoDto;
use Module\Booking\Airport\Domain\Booking\Booking;
use Module\Booking\Common\Application\Factory\AbstractBookingDtoFactory;
use Module\Booking\Common\Application\Factory\BookingPriceDtoFactory;
use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Order\Domain\ValueObject\GuestId;

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
        assert($booking instanceof Booking);

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
            price: $this->bookingPriceDtoFactory->createFromEntity($booking->price()),
            flightNumber: $booking->additionalInfo()->flightNumber(),
            cancelConditions: $booking->cancelConditions() !== null
                ? CancelConditionsDto::fromDomain($booking->cancelConditions())
                : null,
        );
    }
}
