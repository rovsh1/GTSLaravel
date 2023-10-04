<?php

declare(strict_types=1);

namespace Module\Booking\Application\Order\Command;

use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateOrder implements CommandInterface
{
    public function __construct(
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly int $currencyId,
    ) {}
}