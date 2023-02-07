<?php

namespace GTS\Hotel\Application\Command;

use Custom\Framework\Bus\CommandInterface;

class ReserveQuota implements CommandInterface
{
    public function __construct(
        private int $roomId,
        private int $date,
    ) {
    }
}
