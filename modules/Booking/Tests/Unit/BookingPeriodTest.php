<?php

namespace Module\Booking\Tests\Unit;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\BookingPeriod;
use PHPUnit\Framework\TestCase;

class BookingPeriodTest extends TestCase
{
    /**
     * @dataProvider calculateNigthsCountProvider
     * @param string $dateFrom
     * @param string $dateTo
     * @return void
     */
    public function testCalculateNigthsCount(string $dateFrom, string $dateTo, int $nightsCount)
    {
        $period = new BookingPeriod(
            new CarbonImmutable($dateFrom),
            new CarbonImmutable($dateTo),
        );
        $this->assertEquals($period->nightsCount(), $nightsCount);
    }

    public static function calculateNigthsCountProvider()
    {
        return [
            ['2023-06-01', '2023-06-02', 1],
            ['2023-06-01', '2023-06-30', 29],
        ];
    }
}
