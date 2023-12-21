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
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\ValueObject\Date;
use Sdk\Shared\ValueObject\Money;

class HotelPriceMapper
{
    private Booking $booking;

    private CalculatedHotelRoomsPricesDto $supplierPriceDto;

    private CalculatedHotelRoomsPricesDto $clientPriceDto;

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
                $this->supplierPriceDto->currency,
                $accommodation->prices()->supplierPrice()->manualDayValue(),
            ),
            $this->buildRoomPriceItem(
                $this->findRoomCalculationResultDto($accommodation->id(), $this->clientPriceDto),
                $this->clientPriceDto->currency,
                $accommodation->prices()->clientPrice()->manualDayValue(),
            )
        );
    }

    public function buildBookingPrice(): BookingPrices
    {
        $bookingPrices = $this->booking->prices();
        $supplierPriceDto = $this->supplierPriceDto;
        $supplierCurrency = $supplierPriceDto->currency;
        $clientPriceDto = $this->clientPriceDto;
        $clientCurrency = $clientPriceDto->currency;

        return new BookingPrices(
            new BookingPriceItem(
                currency: $supplierCurrency,
                calculatedValue: Money::round($supplierCurrency, $supplierPriceDto->price),
                manualValue: Money::roundNullable($supplierCurrency, $bookingPrices->supplierPrice()->manualValue()),
                penaltyValue: Money::roundNullable($supplierCurrency, $bookingPrices->supplierPrice()->penaltyValue())
            ),
            new BookingPriceItem(
                currency: $clientCurrency,
                calculatedValue: Money::round($clientCurrency, $clientPriceDto->price),
                manualValue: Money::roundNullable($clientCurrency, $bookingPrices->clientPrice()->manualValue()),
                penaltyValue: Money::roundNullable($clientCurrency, $bookingPrices->clientPrice()->penaltyValue())
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

    private function buildRoomPriceItem(
        RoomCalculationResultDto $roomPriceDto,
        CurrencyEnum $currency,
        ?float $manualDayValue
    ): RoomPriceItem {
        $dayPrices = [];
        foreach ($roomPriceDto->dates as $dayPriceDto) {
            $dayPrices[] = new RoomPriceDayPart(
                date: Date::createFromInterface($dayPriceDto->date),
                value: Money::round($currency, $dayPriceDto->value),
                formula: $dayPriceDto->formula
            );
        }

        return new RoomPriceItem(
            dayParts: new RoomPriceDayPartCollection($dayPrices),
            manualDayValue: Money::roundNullable($currency, $manualDayValue),
        );
    }
}
