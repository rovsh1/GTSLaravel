<?php

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Adapter;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomQuotaAdapterInterface;
use Module\Hotel\Moderation\Application\Dto\RoomDto;
use Module\Hotel\Quotation\Application\RequestDto\BookRequestDto;
use Module\Hotel\Quotation\Application\RequestDto\ReserveRequestDto;
use Module\Hotel\Quotation\Application\UseCase\BookQuota;
use Module\Hotel\Quotation\Application\UseCase\CancelBooking;
use Module\Hotel\Quotation\Application\UseCase\GetAvailableQuotas;
use Module\Hotel\Quotation\Application\UseCase\ReserveQuota;

class HotelRoomQuotaAdapter implements HotelRoomQuotaAdapterInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return array<int, RoomDto>
     */
    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return app(GetAvailableQuotas::class)->execute($hotelId, $period, $roomId);
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

    public function getAvailableRooms(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        // TODO: Implement getAvailableRooms() method.
    }
}
