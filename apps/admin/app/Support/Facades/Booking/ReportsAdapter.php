<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use App\Admin\Models\Administrator\Administrator;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static resource generateProfitReport(Administrator $administrator, CarbonPeriod $endPeriod, CarbonPeriod|null $startPeriod = null)
 * @method static resource generateClientOrderReport(Administrator $administrator, CarbonPeriod $endPeriod, CarbonPeriod|null $startPeriod = null, int[] $clientIds = [], int[] $managerIds = [])
 * @method static resource generateHotelBookingsReport(Administrator $administrator, CarbonPeriod $endPeriod, CarbonPeriod|null $startPeriod = null, int[] $managerIds = [])
 * @method static resource generateServiceBookingsReport(Administrator $administrator, CarbonPeriod $endPeriod, int[] $supplierIds, CarbonPeriod|null $startPeriod = null, int[] $serviceTypes = [], int[] $managerIds = [])
 */
class ReportsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\ReportsAdapter::class;
    }
}
