<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Factory;

use Module\Booking\Common\Application\Factory\AbstractBookingDtoFactory;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Hotel\Application\Dto\BookingDto;
use Module\Booking\Hotel\Application\Dto\BookingPriceDto;
use Module\Booking\Hotel\Application\Dto\Details\AdditionalInfoDto;
use Module\Booking\Hotel\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\Hotel\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Hotel\Application\Dto\Details\HotelInfoDto;
use Module\Booking\Hotel\Application\Dto\Details\RoomBookingDto;
use Module\Booking\Hotel\Domain\Entity\Booking;

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
            HotelInfoDto::fromDomain($booking->hotelInfo()),
            BookingPeriodDto::fromDomain($booking->period()),
            $booking->additionalInfo() !== null ? AdditionalInfoDto::fromDomain($booking->additionalInfo()) : null,
            RoomBookingDto::collectionFromDomain($booking->roomBookings()->all()),
            CancelConditionsDto::fromDomain($booking->cancelConditions()),
            BookingPriceDto::fromDomain($booking->price())
        );
    }
}
