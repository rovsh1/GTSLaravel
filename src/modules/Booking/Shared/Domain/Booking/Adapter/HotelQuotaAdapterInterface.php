<?php

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\RequestDto\BookRequestDto;
use Module\Hotel\Quotation\Application\RequestDto\ReserveRequestDto;

interface HotelQuotaAdapterInterface
{
    public function getAvailableCount(int $roomId, CarbonPeriod $period): int;

    public function book(BookRequestDto $requestDto): void;

    public function reserve(ReserveRequestDto $requestDto): void;

    public function cancel(int $bookingId): void;
}
