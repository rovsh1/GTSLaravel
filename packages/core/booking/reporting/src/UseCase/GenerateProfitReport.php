<?php

declare(strict_types=1);

namespace Pkg\Booking\Reporting\UseCase;

use App\Admin\Models\Administrator\Administrator;
use Carbon\CarbonPeriod;
use Pkg\Booking\Reporting\Service\ProfitReportCompiler;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GenerateProfitReport implements UseCaseInterface
{
    public function __construct(
        private readonly ProfitReportCompiler $reportCompiler
    ) {}

    public function execute(
        Administrator $administrator,
        CarbonPeriod $endPeriod,
        ?CarbonPeriod $startPeriod = null
    ): mixed {
        return $this->reportCompiler->generate($administrator, $endPeriod, $startPeriod);
    }
}
