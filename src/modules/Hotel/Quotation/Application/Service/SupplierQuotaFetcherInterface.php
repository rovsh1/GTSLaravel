<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;

interface SupplierQuotaFetcherInterface
{
    public function getAvailableCount(int $roomId, CarbonPeriod $period): int;

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function getAll(int $hotelId, CarbonPeriod $period, ?int $roomId): array;

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId): array;

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function getClosed(int $hotelId, CarbonPeriod $period, ?int $roomId): array;

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId): array;
}
