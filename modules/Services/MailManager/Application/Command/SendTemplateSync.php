<?php

namespace Module\Services\MailManager\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class SendTemplateSync implements CommandInterface
{
    public function __construct(
        public readonly string $template,
        public readonly int $priority = 0,
    ) {
    }
}
