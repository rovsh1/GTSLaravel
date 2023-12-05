<?php

namespace Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Hotel\Pricing\Application\Dto\CalculatedHotelRoomsPricesDto;
use Module\Hotel\Pricing\Application\Dto\RoomCalculationResultDto;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Booking\ValueObject\HotelBooking\RoomPriceDayPart;
use Sdk\Booking\ValueObject\HotelBooking\RoomPriceDayPartCollection;
use Sdk\Booking\ValueObject\HotelBooking\RoomPriceItem;
use Sdk\Booking\ValueObject\HotelBooking\RoomPrices;
use Sdk\Shared\ValueObject\Date;

class HotelPriceMapper
{
    private Booking $booking;

    private CalculatedHotelRoomsPricesDto $supplierPriceDto;

    private CalculatedHotelRoomsPricesDto $clientPriceDto;

    public function __construct(
    ) {}

    public function boot(
        Booking $booking,
        CalculatedHotelRoomsPricesDto $supplierPriceDto,
        CalculatedHotelRoomsPricesDto $clientPriceDto
    ): void {
        $this->booking = $booking;
        $this->supplierPriceDto = $supplierPriceDto;
        $this->clientPriceDto = $clientPriceDto;
    }

    public function buildAccommodationPrice(HotelAccommodation $accommodation): RoomPrices
    {
        return new RoomPrices(
            $this->buildRoomPriceItem(
                $this->findRoomCalculationResultDto($accommodation->id(), $this->supplierPriceDto),
                $accommodation->prices()->supplierPrice()->manualDayValue()
            ),
            $this->buildRoomPriceItem(
                $this->findRoomCalculationResultDto($accommodation->id(), $this->clientPriceDto),
                $accommodation->prices()->clientPrice()->manualDayValue()
            )
        );
    }

    public function buildBookingPrice(): BookingPrices
    {
        $bookingPrices = $this->booking->prices();
        $supplierPriceDto = $this->supplierPriceDto;
        $clientPriceDto = $this->clientPriceDto;

        return new BookingPrices(
            new BookingPriceItem(
                currency: $supplierPriceDto->currency,
                calculatedValue: $supplierPriceDto->price,
                manualValue: $bookingPrices->supplierPrice()->manualValue(),
                penaltyValue: $bookingPrices->supplierPrice()->penaltyValue()
            ),
            new BookingPriceItem(
                currency: $clientPriceDto->currency,
                calculatedValue: $clientPriceDto->price,
                manualValue: $bookingPrices->clientPrice()->manualValue(),
                penaltyValue: $bookingPrices->clientPrice()->penaltyValue()
            ),
        );
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
