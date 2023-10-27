<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Factory;

use Module\Booking\Application\Admin\HotelBooking\Dto\BookingDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\AdditionalInfoDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\BookingPeriodDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\HotelInfoDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking\RoomDayPriceDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBookingDto;
use Module\Booking\Application\Admin\Shared\Factory\AbstractBookingDtoFactory;
use Module\Booking\Application\Admin\Shared\Factory\BookingPriceDtoFactory;
use Module\Booking\Application\Admin\Shared\Factory\StatusDtoFactory;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingCollection;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPriceDayPart;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestId;

class BookingDtoFactory extends AbstractBookingDtoFactory
{
    public function __construct(
        StatusDtoFactory $statusStorage,
        private readonly BookingPriceDtoFactory $bookingPriceDtoFactory,
    ) {
        parent::__construct($statusStorage);
    }

    public function createFromEntity(BookingInterface $booking): BookingDto
    {
        assert($booking instanceof HotelBooking);

        return new BookingDto(
            $booking->id()->value(),
            $this->statusDtoFactory->get($booking->status()),
            $booking->orderId()->value(),
            $booking->createdAt(),
            $booking->creatorId()->value(),
            $this->bookingPriceDtoFactory->createFromEntity($booking->price()),
            CancelConditionsDto::fromDomain($booking->cancelConditions()),
            $booking->note(),
            HotelInfoDto::fromDomain($booking->hotelInfo()),
            BookingPeriodDto::fromDomain($booking->period()),
            $booking->additionalInfo() !== null ? AdditionalInfoDto::fromDomain($booking->additionalInfo()) : null,
            $this->buildGuests($booking->roomBookings()),
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
                price: $this->buildPriceDto($roomBooking->price())
            );
        }

        return $dtos;
    }

    private function buildPriceDto(RoomPrices $price): RoomPriceDto
    {
        return new RoomPriceDto(
            $price->clientPrice()->manualDayValue(),
            $price->supplierPrice()->manualDayValue(),
            array_map(fn($r) => $this->buildRoomDayPriceDto($r), $price->clientPrice()->dayParts()->all()),
            $price->clientPrice()->value(),
            $price->supplierPrice()->value()
        );
    }

    private function buildRoomDayPriceDto(RoomPriceDayPart $r): RoomDayPriceDto
    {
        return new RoomDayPriceDto(
            date: (string)$r->date(),
            baseValue: 1,
            grossValue: $r->value(),
            netValue: $r->value(),
            grossFormula: $r->formula(),
            netFormula: $r->formula()
        );
    }
}
