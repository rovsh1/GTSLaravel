<?php

namespace App\Admin\Support\Facades\Hotel;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;

/**
 * @method static QuotaDto|\Pkg\Supplier\Traveline\Dto\QuotaDto[] getQuotasAvailability(CarbonPeriod $period, int[] $cityIds = [], int[] $hotelIds = [], int[] $roomIds = [], int[] $roomTypeIds = [])
 */
class QuotaAvailabilityAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\QuotaAvailabilityAdapter::class;
    }
}
