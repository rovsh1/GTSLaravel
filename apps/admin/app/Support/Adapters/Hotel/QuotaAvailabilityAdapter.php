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
        array $roomIds = []
    ): array {
        return app(QuotaAvailability\Get::class)->execute($period, $cityIds, $hotelIds, $roomIds);
    }
}
