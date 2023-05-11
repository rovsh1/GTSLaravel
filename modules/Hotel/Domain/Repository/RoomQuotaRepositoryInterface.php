<?php

namespace Module\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Application\Enums\QuotaAvailabilityEnum;
use Module\Hotel\Domain\Entity\RoomQuota;

interface RoomQuotaRepositoryInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @param QuotaAvailabilityEnum|null $availability
     * @return RoomQuota[]
     */
    public function get(int $hotelId, CarbonPeriod $period, ?int $roomId = null, ?QuotaAvailabilityEnum $availability = null): array;

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void;

    public function closeRoomQuota(int $roomId, CarbonPeriod $period): void;

    public function openRoomQuota(int $roomId, CarbonPeriod $period): void;

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void;
}
