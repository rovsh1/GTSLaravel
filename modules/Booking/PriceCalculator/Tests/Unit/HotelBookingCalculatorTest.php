<?php

namespace Module\Booking\PriceCalculator\Tests\Unit;

use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\PriceCalculator\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\CalculateVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\RoomCalculator;
use Module\Shared\Testing\TestCase;

class HotelBookingCalculatorTest extends TestCase
{
    public function testResidentSingleGuest(): void
    {
        $netPrice = 250000.0;
        $calculator = new RoomCalculator($this->makeHotelAdapterStub($netPrice));
        $roomPrice = $calculator->calculate(
            $this->makeVariables(
                true
            )
        );
        //dd($roomPrice);
        /**
         * N = 250 000 * (15/100) = 37 500
         * NDS = (250 000 + 37 500) * (10/100) = 28 750
         * Sb = 250 000 + 37 500 + 28 750 = 316 250
         */
        $this->assertEquals(316250.0, $roomPrice->clientValue());
    }

    public function testNonResidentSingleGuest(): void
    {
        $netPrice = 400000.0;
        $calculator = new RoomCalculator($this->makeHotelAdapterStub($netPrice));
        $roomPrice = $calculator->calculate(
            $this->makeVariables(
                false
            )
        );
        /**
         * T = 300 000 * (15/100) = 45 000
         * N = 400 000 * (15/100) = 60 000
         * NDS = (400 000 + 60 000) * (10/100) = 46 000
         * Sb = 400 000 + 60 000 + 46 000 + 45 000 * 1 = 551 000
         */
        $this->assertEquals(551000.0, $roomPrice->clientValue());
    }

    private function makeVariables(
        int $rateId,
        bool $isResident
    ): CalculateVariables {
        return new CalculateVariables(
            new BookingPeriod(
                now()->modify('+10 days')->toImmutable(),
                now()->modify('+11 days')->toImmutable(),
            ),
            $roomId = 1,
            $rateId,
            $isResident,
            $guestsCount = 1,
            $vatPercent = 10,
            $clientMarkupPercent = 15,
            $touristTax = 300000 * 15 / 100,//23,
            $earlyCheckInPercent = 0,
            $lateCheckOutPercent = 0,
        );
    }

    private function makeHotelAdapterStub(float $netValue)
    {
        $hotelAdapterStub = $this->createStub(HotelAdapterInterface::class);
        $hotelAdapterStub->method('getRoomPrice')->willReturn($netValue);

        return $hotelAdapterStub;
    }
}
