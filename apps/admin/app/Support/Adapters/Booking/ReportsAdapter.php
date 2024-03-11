<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use App\Admin\Models\Administrator\Administrator;
use Carbon\CarbonPeriod;
use Pkg\Booking\Reporting\UseCase\GenerateClientOrderReport;
use Pkg\Booking\Reporting\UseCase\GenerateHotelBookingReport;
use Pkg\Booking\Reporting\UseCase\GenerateProfitReport;
use Pkg\Booking\Reporting\UseCase\GenerateServiceBookingReport;

class ReportsAdapter
{
    public function generateProfitReport(
        Administrator $administrator,
        CarbonPeriod $endPeriod,
        CarbonPeriod|null $startPeriod = null
    ): mixed {
        return app(GenerateProfitReport::class)->execute($administrator, $endPeriod, $startPeriod);
    }

    public function generateClientOrderReport(
        Administrator $administrator,
        CarbonPeriod $endPeriod,
        CarbonPeriod|null $startPeriod = null,
        array $clientIds = [],
        array $managerIds = []
    ): mixed {
        return app(GenerateClientOrderReport::class)->execute(
            $administrator,
            $endPeriod,
            $startPeriod,
            $clientIds,
            $managerIds
        );
    }

    public function generateHotelBookingsReport(
        Administrator $administrator,
        CarbonPeriod $endPeriod,
        CarbonPeriod|null $startPeriod = null,
        array $managerIds = []
    ): mixed {
        return app(GenerateHotelBookingReport::class)->execute($administrator, $endPeriod, $startPeriod, $managerIds);
    }

    public function generateServiceBookingsReport(
        Administrator $administrator,
        CarbonPeriod $endPeriod,
        array $supplierIds,
        CarbonPeriod|null $startPeriod = null,
        array $serviceTypes = [],
        array $managerIds = []
    ): mixed {
        return app(GenerateServiceBookingReport::class)->execute(
            $administrator,
            $endPeriod,
            $supplierIds,
            $startPeriod,
            $serviceTypes,
            $managerIds
        );
    }
}
