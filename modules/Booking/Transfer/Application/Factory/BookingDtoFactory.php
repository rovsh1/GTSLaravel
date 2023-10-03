<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\Factory;

use Module\Booking\Transfer\Application\Response\BookingDto;
use Module\Booking\Transfer\Application\Response\ServiceInfoDto;
use Module\Booking\Transfer\Domain\Booking\Booking;
use Module\Booking\Common\Application\Factory\AbstractBookingDtoFactory;
use Module\Booking\Common\Application\Factory\BookingPriceDtoFactory;
use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditionsDto;

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
            serviceInfo: ServiceInfoDto::fromDomain($booking->serviceInfo()),
            price: $this->bookingPriceDtoFactory->createFromEntity($booking->price()),
            cancelConditions: $booking->cancelConditions() !== null
                ? CancelConditionsDto::fromDomain($booking->cancelConditions())
                : null,
        );
    }
}
