<?php

declare(strict_types=1);

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

use Illuminate\Support\Collection;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\ValueObject\ManualChangablePrice;
use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;
use Module\Booking\PriceCalculator\Domain\Service\BookingCalculatorInterface;

class BookingCalculator implements BookingCalculatorInterface
{
    public function __construct(
        private readonly RoomCalculator $roomCalculator
    ) {}

    public function calculateBoPrice(BookingInterface|Booking $booking): float
    {
        $roomPrices = $this->getCalculatedRoomPrices($booking);
        $bookingPrice = $this->buildBookingPrice($roomPrices);

        return $bookingPrice->boPrice()->value();
    }

    public function calculateHoPrice(BookingInterface|Booking $booking): float
    {
        $roomPrices = $this->getCalculatedRoomPrices($booking);
        $bookingPrice = $this->buildBookingPrice($roomPrices);

        return $bookingPrice->hoPrice()->value();
    }

    private function getCalculatedRoomPrices(Booking $booking): Collection
    {
        $prices = collect();
        foreach ($booking->roomBookings() as $roomBooking) {
            $price = $this->roomCalculator->calculateByBooking(
                $booking,
                $roomBooking->roomInfo()->id(),
                $roomBooking->details()->rateId(),
                $roomBooking->details()->isResident(),
                $roomBooking->guests()->count(),
                $roomBooking->details()->earlyCheckIn()?->priceMarkup()->value(),
                $roomBooking->details()->lateCheckOut()?->priceMarkup()->value()
            );
            $prices->add($price);
        }

        return $prices;
    }

    /**
     * @param Collection<int, RoomPrice> $roomBookingPrices
     * @return BookingPrice
     */
    private function buildBookingPrice(Collection $roomBookingPrices): BookingPrice
    {
        ['netValue' => $netValue, 'hoValue' => $hoValue, 'boValue' => $boValue] = $roomBookingPrices->reduce(
            function (array $result, RoomPrice $roomPrice) {
                $result['netValue'] += $roomPrice->netValue();
                $result['hoValue'] += $roomPrice->hoValue()->value();
                $result['boValue'] += $roomPrice->boValue()->value();

                return $result;
            },
            ['netValue' => 0, 'hoValue' => 0, 'boValue' => 0]
        );

        return new BookingPrice(
            $netValue,
            new ManualChangablePrice($hoValue),
            new ManualChangablePrice($boValue)
        );
    }
}
