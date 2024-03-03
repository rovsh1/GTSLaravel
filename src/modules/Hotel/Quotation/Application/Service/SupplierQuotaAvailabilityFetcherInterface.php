<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;

interface SupplierQuotaAvailabilityFetcherInterface
{
    /**
     * @param CarbonPeriod $period
     * @param int[] $cityIds
     * @param int[] $hotelIds
     * @param int[] $roomIds
     * @return QuotaDto[]
     */
    public function getQuotasAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = []
    ): array;
}
