<?php

namespace Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator;

use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Hotel\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Hotel\Pricing\Application\Dto\RoomCalculationResultDto;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Booking\ValueObject\HotelBooking\RoomPriceDayPart;
use Sdk\Booking\ValueObject\HotelBooking\RoomPriceDayPartCollection;
use Sdk\Booking\ValueObject\HotelBooking\RoomPriceItem;
use Sdk\Booking\ValueObject\HotelBooking\RoomPrices;
use Sdk\Shared\ValueObject\Date;

class AccommodationPriceStorage
{
    public function __construct(
        private readonly AccommodationRepositoryInterface $accommodationRepository,
    ) {
    }

    public function store(
        HotelBooking $bookingDetails,
        CalculatedHotelRoomsPricesDto $supplierPriceDto,
        CalculatedHotelRoomsPricesDto $clientPriceDto
    ): void {
        foreach ($this->accommodationRepository->getByBookingId($bookingDetails->bookingId()) as $accommodation) {
            $roomPrices = $this->buildRoomPrices($accommodation, $supplierPriceDto, $clientPriceDto);
            $accommodation->updatePrices($roomPrices);
        }
    }

    private function findRoomCalculationResultDto(
        AccommodationId $accommodationId,
        CalculatedHotelRoomsPricesDto $roomPriceDto
    ): RoomCalculationResultDto {
        foreach ($roomPriceDto->rooms as $room) {
            if ($room->accommodationId === $accommodationId->value()) {
                return $room;
            }
        }
        throw new \Exception('');
    }

    private function buildRoomPrices(
        HotelAccommodation $accommodation,
        CalculatedHotelRoomsPricesDto $supplierPriceDto,
        CalculatedHotelRoomsPricesDto $clientPriceDto
    ): RoomPrices {
        return new RoomPrices(
            $this->buildRoomPriceItem(
                $this->findRoomCalculationResultDto($accommodation->id(), $supplierPriceDto),
                $accommodation->prices()->supplierPrice()->manualDayValue()
            ),
            $this->buildRoomPriceItem(
                $this->findRoomCalculationResultDto($accommodation->id(), $clientPriceDto),
                $accommodation->prices()->clientPrice()->manualDayValue()
            )
        );
    }

    private function buildRoomPriceItem(RoomCalculationResultDto $roomPriceDto, ?float $manualDayValue): RoomPriceItem
    {
        $dayPrices = [];
        foreach ($roomPriceDto->dates as $dayPriceDto) {
            $dayPrices[] = new RoomPriceDayPart(
                date: Date::createFromInterface($dayPriceDto->date),
                value: $dayPriceDto->value,
                formula: $dayPriceDto->formula
            );
        }

        return new RoomPriceItem(
            dayParts: new RoomPriceDayPartCollection($dayPrices),
            manualDayValue: $manualDayValue
        );
    }
}
