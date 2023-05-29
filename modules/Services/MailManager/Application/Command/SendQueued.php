<?php

namespace Module\Services\MailManager\Application\Command;

use Module\Services\MailManager\Application\Dto\MailMessageDto;
use Sdk\Module\Contracts\Bus\CommandInterface;

class SendQueued implements CommandInterface
{
    public function __construct(
        public readonly MailMessageDto $mailMessage,
        public readonly int $priority = 0,
        public readonly ?array $context = null,
    ) {
    }
}
