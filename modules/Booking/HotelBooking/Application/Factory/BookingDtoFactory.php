<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Factory;

use Module\Booking\Common\Application\Factory\AbstractBookingDtoFactory;
use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\HotelBooking\Application\Dto\BookingDto;
use Module\Booking\HotelBooking\Application\Dto\BookingPriceDto;
use Module\Booking\HotelBooking\Application\Dto\Details\AdditionalInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\HotelBooking\Application\Dto\Details\HotelInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBookingDto;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\Order\Domain\ValueObject\GuestId;

class BookingDtoFactory extends AbstractBookingDtoFactory
{
    public function __construct(
        StatusStorage $statusStorage,
    ) {
        parent::__construct($statusStorage);
    }

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
            $this->buildRooms($booking->roomBookings()),
            CancelConditionsDto::fromDomain($booking->cancelConditions()),
            BookingPriceDto::fromDomain($booking->price()),
            $booking->quotaProcessingMethod(),
        );
    }

    /**
     * @param RoomBookingCollection $roomBookings
     * @return array<int, RoomBookingDto>
     */
    private function buildRooms(RoomBookingCollection $roomBookings): array
    {
        return $roomBookings->map(fn(RoomBooking $roomBooking) => new RoomBookingDto(
            id: $roomBooking->id()->value(),
            status: $roomBooking->status()->value,
            roomInfo: RoomInfoDto::fromDomain($roomBooking->roomInfo()),
            guestIds: $roomBooking->guestIds()->map(fn(GuestId $id) => $id->value()),
            details: RoomBookingDetailsDto::fromDomain($roomBooking->details()),
            price: RoomPriceDto::fromDomain($roomBooking->price())
        ))->all();
    }
}
