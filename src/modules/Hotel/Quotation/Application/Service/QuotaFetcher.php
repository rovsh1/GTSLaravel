<?php

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Service\Factory\SupplierFactory;

class QuotaFetcher
{
    public function __construct(
        private readonly SupplierFactory $supplierFactory
    ) {}

    public function getAvailableCount(int $hotelId, int $roomId, CarbonPeriod $period): int
    {
        return $this->supplierFactory->fetcher($hotelId)->getAvailableCount($roomId, $period);
    }

    public function getAll(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->supplierFactory->fetcher($hotelId)->getAll($hotelId, $period, $roomId);
    }

    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->supplierFactory->fetcher($hotelId)->getAvailable($hotelId, $period, $roomId);
    }

    public function getClosed(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->supplierFactory->fetcher($hotelId)->getClosed($hotelId, $period, $roomId);
    }

    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->supplierFactory->fetcher($hotelId)->getSold($hotelId, $period, $roomId);
    }
}
