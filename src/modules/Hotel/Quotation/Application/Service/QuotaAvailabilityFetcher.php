<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Service\Supplier\Gotostans;
use Module\Hotel\Quotation\Application\Service\Supplier\Traveline;

class QuotaAvailabilityFetcher
{
    public function __construct(
        private readonly Gotostans $gotostansSupplier,
        private readonly Traveline $travelineSupplier,
    ) {}

    public function getQuotasAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = []
    ) {
        $quotas[] = $this->gotostansSupplier->getQuotasAvailability($period, $cityIds, $hotelIds, $roomIds);
        $quotas[] = $this->travelineSupplier->getQuotasAvailability($period, $cityIds, $hotelIds, $roomIds);

        return array_merge(...$quotas);
    }
}