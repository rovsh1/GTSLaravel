<?php

namespace GTS\Administrator\Application\Command\Reference;

use Custom\Framework\Contracts\Bus\CommandInterface;

class StoreCurrency implements CommandInterface
{
    public function __construct(
        public readonly array $params
    ) {}
}
