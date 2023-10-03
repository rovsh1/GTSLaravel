<?php

namespace Module\Booking\Tests\Unit;

use Module\Booking\Domain\HotelBooking\ValueObject\Details\BookingPeriod;
use Module\Booking\PriceCalculator\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\CalculateVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\BORoomPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\MarkupVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\RoomVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\RoomCalculator;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\VariablesBuilder;
use Module\Shared\Testing\TestCase;

class BookingCalculatorTest extends TestCase
{
    public function testResidentSingleGuest(): void
    {
        $netPrice = 250000.0;
        $formula = new BORoomPriceFormula(
            new MarkupVariables(
                vatPercent: 10,
                clientMarkupPercent: 15,
                earlyCheckInPercent: 0,
                lateCheckOutPercent: 0,
                touristTax: 300000 * 15 / 100,//23,
            ),
            new RoomVariables(
                isResident: true,
                guestsCount: 1,
                nightsCount: 1
            )
        );
        $result = $formula->calculate($netPrice);

        $this->assertEquals(316250.0, $result->value);
    }

    public function _testNonResidentSingleGuest(): void
    {
        $netPrice = 400000.0;
        $formula = new BORoomPriceFormula(
            new MarkupVariables(
                vatPercent: 10,
                clientMarkupPercent: 15,
                earlyCheckInPercent: 0,
                lateCheckOutPercent: 0,
                touristTax: 300000 * 15 / 100,//23,
            ),
            new RoomVariables(
                isResident: false,
                guestsCount: 1,
                nightsCount: 1
            )
        );
        $result = $formula->calculate($netPrice);

        $this->assertEquals(551000.0, $result->value);
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
