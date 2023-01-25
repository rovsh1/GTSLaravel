<?php

namespace GTS\Hotel\Application\Command;

use GTS\Shared\Application\Command\CommandInterface;

class ReserveQuota implements CommandInterface
{
    public function __construct(
        private int $roomId,
        private int $date,
    ) {
    }
}
