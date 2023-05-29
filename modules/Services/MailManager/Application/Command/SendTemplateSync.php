<?php

namespace Module\Services\MailManager\Application\Command;

use Sdk\Module\Contracts\Bus\CommandInterface;

class SendTemplateSync implements CommandInterface
{
    public function __construct(
        public readonly string $template,
        public readonly int $priority = 0,
    ) {
    }
}
