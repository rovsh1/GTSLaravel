<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory\HotelBooking;

use Module\Booking\Application\Admin\HotelBooking\Dto\Details\AdditionalInfo\ExternalNumberDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\BookingPeriodDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\HotelInfoDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking\RoomDayPriceDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBookingDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\HotelBookingDto;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingCollection;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPriceDayPart;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Domain\Shared\ValueObject\GuestId;

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
