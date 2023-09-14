<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\BookingCalculatorInterface;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\ValueObject\ManualChangablePrice;

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
            $netSum += $roomBooking->price()->baseValue();
            $hoSum += $roomBooking->price()->netValue();
            $boSum += $roomBooking->price()->grossValue();
        }

        return new BookingPrice(
            $netSum,
            new ManualChangablePrice($hoSum),
            new ManualChangablePrice($boSum),
            null,
            null,
        );
    }
}
