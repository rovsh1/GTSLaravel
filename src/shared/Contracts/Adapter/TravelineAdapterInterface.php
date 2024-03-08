<?php

declare(strict_types=1);

namespace Shared\Contracts\Adapter;

use Carbon\CarbonPeriod;

interface TravelineAdapterInterface
{
    public function isHotelIntegrationEnabled(int $hotelId): bool;

    public function getAvailableCount(int $roomId, CarbonPeriod $period): int;

    public function getAllQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array;

    public function getAvailableQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array;

    public function getClosedQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array;

    public function getSoldQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId): array;

    public function getQuotasAvailability(CarbonPeriod $period, array $cityIds = [], array $hotelIds = [], array $roomIds = []): array;

    public function update(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void;

    public function open(int $roomId, CarbonPeriod $period): void;

    public function close(int $roomId, CarbonPeriod $period): void;

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void;

    public function book(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void;

    public function reserve(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void;

    public function cancelBooking(int $bookingId): void;

    public function hasAvailable(int $roomId, CarbonPeriod $period, int $count): bool;
}
