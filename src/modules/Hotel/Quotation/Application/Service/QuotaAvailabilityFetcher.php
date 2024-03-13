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
        array $roomIds = [],
        array $roomTypeIds = []
    ) {
        $quotas[] = $this->gotostansSupplier->getQuotasAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
        $quotas[] = $this->travelineSupplier->getQuotasAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);

        return array_merge(...$quotas);
    }

    public function getQuotasSoldAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ) {
        $quotas[] = $this->gotostansSupplier->getQuotasSoldAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
        $quotas[] = $this->travelineSupplier->getQuotasSoldAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);

        return array_merge(...$quotas);
    }

    public function getQuotasClosedAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ) {
        $quotas[] = $this->gotostansSupplier->getQuotasClosedAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
        $quotas[] = $this->travelineSupplier->getQuotasClosedAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);

        return array_merge(...$quotas);
    }

    public function getQuotasAvailableAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ) {
        $quotas[] = $this->gotostansSupplier->getQuotasAvailableAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
        $quotas[] = $this->travelineSupplier->getQuotasAvailableAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);

        return array_merge(...$quotas);
    }
}
