<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\UseCase;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Quotation\Infrastructure\Model\Quota;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableCount implements UseCaseInterface
{
    public function execute(int $roomId, CarbonPeriod $period): int
    {
        return (int)DB::table(
            Quota::query()
                ->whereRoomId($roomId)
                ->wherePeriod($period)
                ->withCountColumns(),
            't'
        )->min('t.count_available');
    }
}
