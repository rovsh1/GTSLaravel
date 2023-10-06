<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\Factory;

use Module\Booking\Application\HotelBooking\Dto\BookingDto;
use Module\Booking\Application\HotelBooking\Dto\Details\AdditionalInfoDto;
use Module\Booking\Application\HotelBooking\Dto\Details\BookingPeriodDto;
use Module\Booking\Application\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\HotelBooking\Dto\Details\HotelInfoDto;
use Module\Booking\Application\HotelBooking\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\Application\HotelBooking\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\Application\HotelBooking\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\Application\HotelBooking\Dto\Details\RoomBookingDto;
use Module\Booking\Application\Shared\Factory\AbstractBookingDtoFactory;
use Module\Booking\Application\Shared\Factory\BookingPriceDtoFactory;
use Module\Booking\Application\Shared\Service\StatusStorage;
use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBookingCollection;
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
        assert($booking instanceof HotelBooking);

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
            $this->buildGuests($booking->roomBookings()),
            CancelConditionsDto::fromDomain($booking->cancelConditions()),
            $this->bookingPriceDtoFactory->createFromEntity($booking->price()),
            $booking->quotaProcessingMethod(),
        );
    }

    /**
     * @param RoomBookingCollection $roomBookings
     * @return array<int, RoomBookingDto>
     */
    private function buildGuests(RoomBookingCollection $roomBookings): array
    {
        $dtos = [];
        foreach ($roomBookings as $roomBooking) {
            $dtos[] = new RoomBookingDto(
                id: $roomBooking->id()->value(),
                roomInfo: RoomInfoDto::fromDomain($roomBooking->roomInfo()),
                guestIds: $roomBooking->guestIds()->map(fn(GuestId $id) => $id->value()),
                details: RoomBookingDetailsDto::fromDomain($roomBooking->details()),
                price: RoomPriceDto::fromDomain($roomBooking->price())
            );
        }

        return $dtos;
    }
}
