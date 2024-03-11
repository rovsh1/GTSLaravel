<?php

declare(strict_types=1);

namespace Pkg\Booking\Reporting\UseCase;

use App\Admin\Models\Administrator\Administrator;
use Carbon\CarbonPeriod;
use Pkg\Booking\Reporting\Service\ClientOrderReportCompiler;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GenerateClientOrderReport implements UseCaseInterface
{
    public function __construct(
        private readonly ClientOrderReportCompiler $reportCompiler,
    ) {}

    public function execute(
        Administrator $administrator,
        CarbonPeriod $endPeriod,
        ?CarbonPeriod $startPeriod = null,
        array $clientIds = [],
        array $managerIds = []
    ): mixed {
        return $this->reportCompiler->generate($administrator, $endPeriod, $startPeriod, $clientIds, $managerIds);
    }
}
