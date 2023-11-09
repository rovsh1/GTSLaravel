<?php

namespace Module\Hotel\Quotation\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Domain\Entity\RoomQuota;

interface RoomQuotaRepositoryInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return RoomQuota[]
     */
    public function get(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array;

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return RoomQuota[]
     */
    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array;

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return RoomQuota[]
     */
    public function getStopped(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array;

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return RoomQuota[]
     */
    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array;

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void;

    public function closeRoomQuota(int $roomId, CarbonPeriod $period): void;

    public function openRoomQuota(int $roomId, CarbonPeriod $period): void;
}
