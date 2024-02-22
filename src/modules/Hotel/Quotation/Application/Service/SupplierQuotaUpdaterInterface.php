<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\CarbonPeriod;

interface SupplierQuotaUpdaterInterface
{
    public function update(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void;

    public function open(int $roomId, CarbonPeriod $period): void;

    public function close(int $roomId, CarbonPeriod $period): void;

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void;
}
