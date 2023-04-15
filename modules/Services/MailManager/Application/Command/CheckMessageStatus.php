<?php

namespace Module\Services\MailManager\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class CheckMessageStatus implements CommandInterface
{
    public function __construct(
        public readonly string $uuid,
    ) {
    }
}
