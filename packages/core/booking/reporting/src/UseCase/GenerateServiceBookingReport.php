<?php

declare(strict_types=1);

namespace Pkg\Booking\Reporting\UseCase;

use App\Admin\Models\Administrator\Administrator;
use Carbon\CarbonPeriod;
use Pkg\Booking\Reporting\Service\ServiceBookingReportCompiler;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GenerateServiceBookingReport implements UseCaseInterface
{
    public function __construct(
        private readonly ServiceBookingReportCompiler $reportCompiler
    ) {}

    public function execute(
        Administrator $administrator,
        CarbonPeriod $endPeriod,
        array $supplierIds,
        ?CarbonPeriod $startPeriod = null,
        array $serviceTypes = [],
        array $managerIds = []
    ): mixed {
        return $this->reportCompiler->generate(
            $administrator,
            $endPeriod,
            $supplierIds,
            $startPeriod,
            $serviceTypes,
            $managerIds
        );
    }
}
