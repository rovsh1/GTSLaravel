<?php

namespace Module\Support\MailManager\Application\Command;

use Sdk\Module\Contracts\Bus\CommandInterface;

class CheckMessageStatus implements CommandInterface
{
    public function __construct(
        public readonly string $uuid,
    ) {
    }
}
