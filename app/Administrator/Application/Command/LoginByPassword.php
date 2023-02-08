<?php

namespace GTS\Administrator\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class LoginByPassword implements CommandInterface
{
    public function __construct(
        public readonly string $login,
        public readonly string $password
    ) {}
}
