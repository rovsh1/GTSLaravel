<?php

declare(strict_types=1);

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\ValueObject\ManualChangablePrice;
use Module\Booking\PriceCalculator\Domain\Service\BookingCalculatorInterface;

class BookingCalculator implements BookingCalculatorInterface
{
    public function calculateBoPrice(BookingInterface|Booking $booking): float
    {
        $bookingPrice = $this->buildBookingPrice($booking);

        return $bookingPrice->boPrice()->value();
    }

    public function calculateHoPrice(BookingInterface|Booking $booking): float
    {
        $bookingPrice = $this->buildBookingPrice($booking);

        return $bookingPrice->hoPrice()->value();
    }

    private function buildBookingPrice(Booking $booking): BookingPrice
    {
        $netSum = 0;
        $hoSum = 0;
        $boSum = 0;

        foreach ($booking->roomBookings() as $roomBooking) {
            $netSum += $roomBooking->price()->netValue();
            $hoSum += $roomBooking->price()->hoValue();
            $boSum += $roomBooking->price()->boValue();
        }

        return new BookingPrice(
            $netSum,
            new ManualChangablePrice($hoSum),
            new ManualChangablePrice($boSum)
        );
    }
}
