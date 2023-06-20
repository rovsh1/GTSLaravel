<?php

namespace Module\Booking\PriceCalculator\Tests\Unit;

use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\RoomVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\RoomCalculator;
use Module\Shared\Testing\TestCase;

class HotelBookingCalculatorTest extends TestCase
{
    public function testRoomFormula(): void
    {
        $vatPercent = 10;
        $clientMarkupPercent = 15;
        $conditionalMarkupPercent = 50;
        $variables = new RoomVariables(
            $vatPercent,
            $clientMarkupPercent,
            $conditionalMarkupPercent
        );
        $calculator = new RoomCalculator();
        $roomPrice = $calculator->calculate();
        /**
         * N = 250 000 * (15/100) = 37 500
        NDS = (250 000 + 37 500) * (10/100) = 28 750
        Sb = 250 000 + 37 500 + 28 750 = 316 250 ≈ 317 000 (округляем в большую сторону до тысячных)
         */
        $this->assertEquals(true);
    }
}
