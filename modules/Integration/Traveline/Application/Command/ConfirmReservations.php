<?php

namespace Module\Integration\Traveline\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class ConfirmReservations implements CommandInterface
{
    public function __construct(
        public readonly array $reservations
    ) {}
}
