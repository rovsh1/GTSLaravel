<?php

namespace GTS\Administrator\Application\Command\Reference;

use Custom\Framework\Contracts\Bus\CommandInterface;

class UpdateCurrency implements CommandInterface
{
    public function __construct(
        public readonly int $id,
        public readonly array $params
    ) {}
}
