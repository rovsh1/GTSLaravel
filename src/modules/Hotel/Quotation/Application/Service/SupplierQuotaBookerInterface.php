<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;

interface SupplierQuotaBookerInterface
{
    public function book(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void;

    public function reserve(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void;

    public function cancelBooking(int $bookingId): void;

    public function hasAvailable(int $roomId, CarbonPeriod $period, int $count): bool;
}
