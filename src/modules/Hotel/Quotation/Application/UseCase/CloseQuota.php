<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\UseCase;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Service\QuotaUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CloseQuota implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaUpdater $quotaUpdater,
    ) {
    }

    public function execute(int $roomId, CarbonPeriod $period): void
    {
        $this->quotaUpdater->close($roomId, $period);
    }
}
