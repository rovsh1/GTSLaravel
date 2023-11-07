<?php

namespace Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator;

use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Shared\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPriceDayPart;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPriceDayPartCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Pricing\Application\Dto\RoomCalculationResultDto;
use Module\Shared\ValueObject\Date;

class RoomBookingPriceStorage
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomRepository,
    ) {
    }

    public function store(
        HotelBooking $bookingDetails,
        CalculatedHotelRoomsPricesDto $supplierPriceDto,
        CalculatedHotelRoomsPricesDto $clientPriceDto
    ): void {
        foreach ($bookingDetails->roomBookings() as $roomBookingId) {
            $roomBooking = $this->roomRepository->findOrFail($roomBookingId);

            $roomPrices = $this->buildRoomPrices($roomBooking, $supplierPriceDto, $clientPriceDto);
            $roomBooking->updatePrices($roomPrices);

            $this->roomRepository->store($roomBooking);
        }
    }

    private function findRoomCalculationResultDto(
        RoomBookingId $roomBookingId,
        CalculatedHotelRoomsPricesDto $roomPriceDto
    ): RoomCalculationResultDto {
        foreach ($roomPriceDto->rooms as $room) {
            if ($room->accommodationId === $roomBookingId->value()) {
                return $room;
            }
        }
        throw new \Exception('');
    }

    private function buildRoomPrices(
        HotelRoomBooking $roomBooking,
        CalculatedHotelRoomsPricesDto $supplierPriceDto,
        CalculatedHotelRoomsPricesDto $clientPriceDto
    ): RoomPrices {
        return new RoomPrices(
            $this->buildRoomPriceItem(
                $this->findRoomCalculationResultDto($roomBooking->id(), $supplierPriceDto),
                $roomBooking->prices()->supplierPrice()->manualDayValue()
            ),
            $this->buildRoomPriceItem(
                $this->findRoomCalculationResultDto($roomBooking->id(), $clientPriceDto),
                $roomBooking->prices()->clientPrice()->manualDayValue()
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
