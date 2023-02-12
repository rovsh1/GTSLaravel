<?php

namespace GTS\Integration\Traveline\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class UpdateQuotasAndPlans implements CommandInterface
{
    public function __construct(
        public readonly int   $hotelId,
        public readonly array $updates
    ) {}
}
