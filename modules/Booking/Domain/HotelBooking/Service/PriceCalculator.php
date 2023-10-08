<?php

namespace Module\Booking\Domain\HotelBooking\Service;

use Module\Booking\Application\Admin\HotelBooking\Builder\CalculateHotelPriceRequestDtoBuilder;
use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPriceDayPart;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPriceDayPartCollection;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPriceItem;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\BookingPriceItem;
use Module\Pricing\Application\Dto\RoomCalculationResultDto;
use Module\Pricing\Application\ResponseDto\CalculateHotelRoomsPriceResponseDto;
use Module\Pricing\Application\UseCase\CalculateHotelRoomsPrice;
use Module\Shared\ValueObject\Date;

class PriceCalculator
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomBookingRepositoryInterface $roomRepository,
        private readonly CalculateHotelPriceRequestDtoBuilder $calculateHotelPriceRequestDtoBuilder,
    ) {
    }

    public function calculate(BookingId $bookingId)
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $r = app(CalculateHotelRoomsPrice::class)->execute(
            $this->calculateHotelPriceRequestDtoBuilder
                ->booking($booking)
                ->build()
        );

        $booking->updatePrice($this->buildBookingPrice($r));
        $this->bookingRepository->store($booking);

        $this->storeRoomPrices($booking, $r);
    }

    private function buildBookingPrice(CalculateHotelRoomsPriceResponseDto $responseDto): BookingPrice
    {
        return new BookingPrice(
            BookingPriceItem::createEmpty($responseDto->currency),
            new BookingPriceItem(
                currency: $responseDto->currency,
                calculatedValue: $responseDto->price,
                manualValue: null,
                penaltyValue: null
            ),
        );
    }

    private function storeRoomPrices(
        HotelBooking $booking,
        CalculateHotelRoomsPriceResponseDto $responseDto
    ): void {
        $findRoom = function (int $roomId) use ($responseDto): RoomCalculationResultDto {
            foreach ($responseDto->rooms as $room) {
                if ($room->accommodationId === $roomId) {
                    return $room;
                }
            }
            throw new \Exception("");
        };

        foreach ($booking->roomBookings() as $roomBooking) {
            $roomPriceDto = $findRoom($roomBooking->id()->value());
            $dayPrices = [];
            foreach ($roomPriceDto->dates as $dayPriceDto) {
                $dayPrices[] = new RoomPriceDayPart(
                    date: Date::createFromInterface($dayPriceDto->date),
                    value: $dayPriceDto->value,
                    formula: $dayPriceDto->formula
                );
            }
            $roomPrice = new RoomPrice(
                RoomPriceItem::createEmpty(),
                new RoomPriceItem(
                    dayParts: new RoomPriceDayPartCollection($dayPrices),
                    manualDayValue: null
                )
            );

            $roomBooking->updatePrice($roomPrice);
            $this->roomRepository->store($roomBooking);
        }
    }
}