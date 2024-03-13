<?php

namespace App\Admin\Support\Adapters\Hotel;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\UseCase\QuotaAvailability;

class QuotaAvailabilityAdapter
{
    public function getQuotasAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ): array {
        return app(QuotaAvailability\Get::class)->execute($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
    }

    public function getQuotasSoldAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ): array {
        return app(QuotaAvailability\GetSold::class)->execute($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
    }

    public function getQuotasClosedAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ): array {
        return app(QuotaAvailability\GetClosed::class)->execute($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
    }

    public function getQuotasAvailableAvailability(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ): array {
        return app(QuotaAvailability\GetAvailable::class)->execute(
            $period,
            $cityIds,
            $hotelIds,
            $roomIds,
            $roomTypeIds
        );
    }
}
