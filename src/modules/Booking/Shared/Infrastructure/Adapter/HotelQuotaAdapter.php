<?php

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelQuotaAdapterInterface;
use Module\Hotel\Quotation\Application\RequestDto\BookRequestDto;
use Module\Hotel\Quotation\Application\RequestDto\ReserveRequestDto;
use Module\Hotel\Quotation\Application\UseCase\BookQuota;
use Module\Hotel\Quotation\Application\UseCase\CancelBooking;
use Module\Hotel\Quotation\Application\UseCase\GetAvailableCount;
use Module\Hotel\Quotation\Application\UseCase\ReserveQuota;

class HotelQuotaAdapter implements HotelQuotaAdapterInterface
{
    public function getAvailableCount(int $hotelId, int $roomId, CarbonPeriod $period): int
    {
        return app(GetAvailableCount::class)->execute($hotelId, $roomId, $period);
    }

    public function book(BookRequestDto $requestDto): void
    {
        app(BookQuota::class)->execute($requestDto);
    }

    public function reserve(ReserveRequestDto $requestDto): void
    {
        app(ReserveQuota::class)->execute($requestDto);
    }

    public function cancel(int $bookingId): void
    {
        app(CancelBooking::class)->execute($bookingId);
    }
}
