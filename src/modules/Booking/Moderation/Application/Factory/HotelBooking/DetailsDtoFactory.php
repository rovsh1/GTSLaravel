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
use Module\Booking\Moderation\Application\Dto\GuestDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\HotelBookingDto;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Sdk\Booking\ValueObject\HotelBooking\RoomPriceDayPart;
use Sdk\Booking\ValueObject\HotelBooking\RoomPrices;
use Shared\Contracts\Adapter\TravelineAdapterInterface;

class DetailsDtoFactory
{
    public function __construct(
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly TravelineAdapterInterface $travelineAdapter,
    ) {}

    public function build(HotelBooking $details): HotelBookingDto
    {
        $accommodations = $this->accommodationRepository->getByBookingId($details->bookingId());
        $roomDtos = $this->buildHotelRooms($accommodations);

        return new HotelBookingDto(
            $details->id()->value(),
            HotelInfoDto::fromDomain($details->hotelInfo()),
            BookingPeriodDto::fromDomain($details->bookingPeriod()),
            $roomDtos,
            $details->externalNumber()
                ? ExternalNumberDto::fromDomain($details->externalNumber())
                : null,
            $details->quotaProcessingMethod(),
            $this->travelineAdapter->isHotelIntegrationEnabled($details->hotelInfo()->id()),
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
            $guests = $this->guestRepository->get($accommodation->guestIds());
            $dtos[] = new AccommodationDto(
                id: $accommodation->id()->value(),
                roomInfo: RoomInfoDto::fromDomain($accommodation->roomInfo()),
                guests: GuestDto::collectionFromDomain($guests),
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
            $this->buildRoomDayPricesDto(
                $price->clientPrice()->dayParts()->all(),
                $price->supplierPrice()->dayParts()->all()
            ),
            $price->clientPrice()->value(),
            $price->supplierPrice()->value()
        );
    }

    /**
     * @param RoomPriceDayPart[] $clientDayParts
     * @param RoomPriceDayPart[] $supplierDayParts
     * @return RoomDayPriceDto[]
     */
    private function buildRoomDayPricesDto(array $clientDayParts, array $supplierDayParts): array
    {
        if (count($clientDayParts) !== count($supplierDayParts)) {
            return [];
        }

        $dayPrices = [];
        foreach ($clientDayParts as $index => $clientDayPart) {
            $supplierDayPart = $supplierDayParts[$index];
            $dayPrices[] = new RoomDayPriceDto(
                date: (string)$clientDayPart->date(),
                baseValue: 1,
                grossValue: $clientDayPart->value(),
                netValue: $supplierDayPart->value(),
                grossFormula: $clientDayPart->formula(),
                netFormula: $supplierDayPart->formula()
            );
        }

        return $dayPrices;
    }
}
