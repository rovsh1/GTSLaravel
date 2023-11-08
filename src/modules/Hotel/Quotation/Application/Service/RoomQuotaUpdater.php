<?php

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Exception\RoomNotFound;
use Module\Hotel\Quotation\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Quotation\Domain\Repository\RoomQuotaRepositoryInterface;

class RoomQuotaUpdater
{
    public function __construct(
        private readonly RoomQuotaRepositoryInterface $quotaRepository,
        private readonly HotelAdapterInterface $hotelAdapter
    ) {
    }

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $this->ensureRoomExists($roomId);
        $this->quotaRepository->updateRoomQuota($roomId, $period, $quota, $releaseDays);
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->ensureRoomExists($roomId);
        $this->quotaRepository->openRoomQuota($roomId, $period);
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->ensureRoomExists($roomId);
        $this->quotaRepository->closeRoomQuota($roomId, $period);
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->ensureRoomExists($roomId);
        $this->quotaRepository->resetRoomQuota($roomId, $period);
    }

    private function ensureRoomExists(int $roomId): void
    {
        if (!$this->hotelAdapter->isRoomExists($roomId)) {
            throw new RoomNotFound("Room with id $roomId not found");
        }
    }
}
