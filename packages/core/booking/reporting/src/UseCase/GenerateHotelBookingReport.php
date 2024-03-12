<?php

declare(strict_types=1);

namespace Pkg\Booking\Reporting\UseCase;

use App\Admin\Models\Administrator\Administrator;
use Carbon\CarbonPeriod;
use Pkg\Booking\Reporting\Service\HotelBookingReportCompiler;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GenerateHotelBookingReport implements UseCaseInterface
{
    public function __construct(
        private readonly HotelBookingReportCompiler $reportCompiler,
    ) {}

    public function execute(
        Administrator $administrator,
        CarbonPeriod $endPeriod,
        ?CarbonPeriod $startPeriod = null,
        array $managerIds = []
    ): mixed {
        return $this->reportCompiler->generate($administrator, $endPeriod, $startPeriod, $managerIds);
    }
}
