<?php

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Exception\RoomNotFound;
use Module\Hotel\Quotation\Application\Service\Factory\SupplierFactory;
use Module\Hotel\Quotation\Domain\Adapter\HotelAdapterInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class QuotaUpdater
{
    private static array $roomHotels = [];

    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly SupplierFactory $supplierFactory,
    ) {}

    public function update(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $this->ensure($roomId);
        $this->getUpdater($roomId)->update($roomId, $period, $quota, $releaseDays);
    }

    public function open(int $roomId, CarbonPeriod $period): void
    {
        $this->ensure($roomId);
        $this->getUpdater($roomId)->open($roomId, $period);
    }

    public function close(int $roomId, CarbonPeriod $period): void
    {
        $this->ensure($roomId);
        $this->getUpdater($roomId)->close($roomId, $period);
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        $this->ensure($roomId);
        $this->getUpdater($roomId)->resetRoomQuota($roomId, $period);
    }

    private function ensure(int $roomId): void
    {
        if (!$this->hotelAdapter->isRoomExists($roomId)) {
            throw new RoomNotFound($roomId);
        }
    }

    private function getUpdater(int $roomId): SupplierQuotaUpdaterInterface
    {
        $hotelId = static::$roomHotels[$roomId] ?? null;
        if ($hotelId !== null) {
            return static::$roomHotels[$roomId];
        }

        $hotelId = $this->hotelAdapter->getRoomHotelId($roomId);
        if ($hotelId === null) {
            throw new EntityNotFoundException('Room not found');
        }

        static::$roomHotels[$roomId] = $hotelId;

        return $this->supplierFactory->updater($hotelId);
    }
}
