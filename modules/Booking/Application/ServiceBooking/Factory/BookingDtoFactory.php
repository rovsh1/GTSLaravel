<?php

declare(strict_types=1);

namespace Module\Booking\Application\ServiceBooking\Factory;

use Module\Booking\Application\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\ServiceBooking\Dto\BookingDto;
use Module\Booking\Application\Shared\Factory\AbstractBookingDtoFactory;
use Module\Booking\Application\Shared\Factory\BookingPriceDtoFactory;
use Module\Booking\Application\Shared\Service\StatusStorage;
use Module\Booking\Domain\ServiceBooking\ServiceBooking;
use Module\Booking\Domain\Shared\Entity\BookingInterface;

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
        assert($booking instanceof ServiceBooking);

        return new BookingDto(
            id: $booking->id()->value(),
            status: $this->statusStorage->get($booking->status()),
            orderId: $booking->orderId()->value(),
            createdAt: $booking->createdAt(),
            creatorId: $booking->creatorId()->value(),
            note: $booking->note(),
            cancelConditions: $booking->cancelConditions() !== null
                ? CancelConditionsDto::fromDomain($booking->cancelConditions())
                : null,
            price: $this->bookingPriceDtoFactory->createFromEntity($booking->price()),
        );
    }
}
