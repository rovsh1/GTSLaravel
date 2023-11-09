<?php

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Carbon\CarbonPeriod;
use Module\Hotel\Moderation\Application\Dto\RoomDto;
use Module\Hotel\Quotation\Application\RequestDto\BookRequestDto;
use Module\Hotel\Quotation\Application\RequestDto\ReserveRequestDto;

interface HotelRoomQuotaAdapterInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return array<int, RoomDto>
     */
    public function getAvailableRooms(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array;

    public function book(BookRequestDto $requestDto): void;

    public function reserve(ReserveRequestDto $requestDto): void;

    public function cancel(int $bookingId): void;
}
