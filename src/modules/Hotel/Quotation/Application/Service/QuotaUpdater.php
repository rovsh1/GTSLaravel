<?php

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Exception\RoomNotFound;
use Module\Hotel\Quotation\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Quotation\Domain\Repository\QuotaRepositoryInterface;
use Module\Hotel\Quotation\Domain\ValueObject\RoomId;

class QuotaUpdater
{
    public function __construct(
        private readonly QuotaRepositoryInterface $quotaRepository,
        private readonly HotelAdapterInterface $hotelAdapter
    ) {}

    public function update(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $this->ensure($roomId);
        $this->quotaRepository->update(new RoomId($roomId), $period, $quota, $releaseDays);
    }

    public function open(int $roomId, CarbonPeriod $period): void
    {
        $this->ensure($roomId);
        $this->quotaRepository->open(new RoomId($roomId), $period);
    }

    public function close(int $roomId, CarbonPeriod $period): void
    {
        $this->ensure($roomId);
        $this->quotaRepository->close(new RoomId($roomId), $period);
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->ensure($roomId);
        $this->quotaRepository->reset(new RoomId($roomId), $period);
    }

    private function ensure(int $roomId): void
    {
        if (!$this->hotelAdapter->isRoomExists($roomId)) {
            throw new RoomNotFound($roomId);
        }
    }
}
