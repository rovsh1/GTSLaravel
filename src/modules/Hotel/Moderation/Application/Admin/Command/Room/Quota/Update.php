<?php

namespace Module\Hotel\Moderation\Application\Admin\Command\Room\Quota;

use Carbon\CarbonPeriod;
use Sdk\Module\Contracts\Bus\CommandInterface;

class Update implements CommandInterface
{
    public function __construct(
        public readonly int          $roomId,
        public readonly CarbonPeriod $period,
        public readonly ?int          $quota,
        public readonly ?int          $releaseDays,
    ) {}
}
