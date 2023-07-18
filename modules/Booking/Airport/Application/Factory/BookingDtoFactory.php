<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Factory;

use Module\Booking\Airport\Application\Dto\BookingDto;
use Module\Booking\Airport\Application\Dto\Details\AirportInfoDto;
use Module\Booking\Airport\Application\Dto\Details\ServiceInfoDto;
use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Common\Application\Factory\AbstractBookingDtoFactory;
use Module\Booking\Common\Domain\Entity\BookingInterface;

class BookingDtoFactory extends AbstractBookingDtoFactory
{
    public function createFromEntity(BookingInterface $booking): BookingDto
    {
        assert($booking instanceof Booking);

        return new BookingDto(
            $booking->id()->value(),
            $this->statusStorage->get($booking->status()),
            $booking->orderId()->value(),
            $booking->createdAt(),
            $booking->creatorId()->value(),
            $booking->note(),
            AirportInfoDto::fromDomain($booking->airportInfo()),
            ServiceInfoDto::fromDomain($booking->serviceInfo()),
            $booking->date(),
        );
    }
}
