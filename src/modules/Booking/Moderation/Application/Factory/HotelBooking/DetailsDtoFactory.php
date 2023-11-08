<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory\HotelBooking;

use Module\Booking\Moderation\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\Moderation\Application\Dto\Details\ExternalNumberDto;
use Module\Booking\Moderation\Application\Dto\Details\HotelInfoDto;
use Module\Booking\Moderation\Application\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\Moderation\Application\Dto\Details\RoomBooking\RoomDayPriceDto;
use Module\Booking\Moderation\Application\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\Moderation\Application\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\Moderation\Application\Dto\Details\RoomBookingDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\HotelBookingDto;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPriceDayPart;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;

class DetailsDtoFactory
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {
    }

    public function build(HotelBooking $details): HotelBookingDto
    {
        $roomBookings = $this->roomBookingRepository->get($details->roomBookings());
        $roomDtos = $this->buildHotelRooms($roomBookings);

        return new HotelBookingDto(
            $details->id()->value(),
            HotelInfoDto::fromDomain($details->hotelInfo()),
            BookingPeriodDto::fromDomain($details->bookingPeriod()),
            $roomDtos,
            $details->externalNumber()
                ? ExternalNumberDto::fromDomain($details->externalNumber())
                : null,
            $details->quotaProcessingMethod()
        );
    }

    /**
     * @param RoomBookingCollection $roomBookings
     * @return array<int, RoomBookingDto>
     */
    private function buildHotelRooms(RoomBookingCollection $roomBookings): array
    {
        $dtos = [];
        foreach ($roomBookings as $roomBooking) {
            $dtos[] = new RoomBookingDto(
                id: $roomBooking->id()->value(),
                roomInfo: RoomInfoDto::fromDomain($roomBooking->roomInfo()),
                guestIds: $roomBooking->guestIds()->map(fn(GuestId $id) => $id->value()),
                details: RoomBookingDetailsDto::fromDomain($roomBooking->details()),
                price: $this->buildHotelRoomPriceDto($roomBooking->prices())
            );
        }

        return $dtos;
    }

    private function buildHotelRoomPriceDto(RoomPrices $price): RoomPriceDto
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