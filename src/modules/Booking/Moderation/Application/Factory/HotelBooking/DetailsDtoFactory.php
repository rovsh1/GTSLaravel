<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory\HotelBooking;

use Module\Booking\Moderation\Application\Dto\Details\Accommodation\AccommodationDetailsDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomDayPriceDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomInfoDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomPriceDto;
use Module\Booking\Moderation\Application\Dto\Details\AccommodationDto;
use Module\Booking\Moderation\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\Moderation\Application\Dto\Details\ExternalNumberDto;
use Module\Booking\Moderation\Application\Dto\Details\HotelInfoDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\HotelBookingDto;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPriceDayPart;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;

class DetailsDtoFactory
{
    public function __construct(
        private readonly AccommodationRepositoryInterface $accommodationRepository
    ) {
    }

    public function build(HotelBooking $details): HotelBookingDto
    {
        $accommodations = $this->accommodationRepository->get($details->accommodations());
        $roomDtos = $this->buildHotelRooms($accommodations);

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
     * @param AccommodationCollection $accommodations
     * @return array<int, AccommodationDto>
     */
    private function buildHotelRooms(AccommodationCollection $accommodations): array
    {
        $dtos = [];
        foreach ($accommodations as $accommodation) {
            $dtos[] = new AccommodationDto(
                id: $accommodation->id()->value(),
                roomInfo: RoomInfoDto::fromDomain($accommodation->roomInfo()),
                guestIds: $accommodation->guestIds()->map(fn(GuestId $id) => $id->value()),
                details: AccommodationDetailsDto::fromDomain($accommodation->details()),
                price: $this->buildHotelRoomPriceDto($accommodation->prices())
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
