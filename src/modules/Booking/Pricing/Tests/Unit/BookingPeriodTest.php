<?php

namespace Module\Booking\Pricing\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sdk\Booking\ValueObject\BookingPeriod;
use Sdk\Module\Support\DateTimeImmutable;

class BookingPeriodTest extends TestCase
{
    /**
     * @dataProvider calculateDaysCountProvider
     * @param string $dateFrom
     * @param string $dateTo
     * @return void
     */
    public function testCalculateNigthsCount(string $dateFrom, string $dateTo, int $daysCount)
    {
        $period = new BookingPeriod(
            new DateTimeImmutable($dateFrom),
            new DateTimeImmutable($dateTo),
        );
        $this->assertEquals($period->daysCount(), $daysCount);
    }

    public static function calculateDaysCountProvider()
    {
        return [
            ['2023-06-01', '2023-06-02', 1],
            ['2023-06-01', '2023-06-04', 3],
            ['2023-06-17', '2023-06-23', 6],
        ];
    }
}
