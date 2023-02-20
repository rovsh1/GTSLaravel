<?php

namespace GTS\Administrator\Application\Command\Reference;

use Custom\Framework\Contracts\Bus\CommandInterface;

class StoreCurrency implements CommandInterface
{
    public function __construct(
        public readonly string $name,
        public readonly int $code_num,
        public readonly string $code_char,
        public readonly string $sign
    ) {}
}
