<?php

namespace Module\Services\MailManager\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Services\MailManager\Application\Dto\MailMessageDto;

class SendQueued implements CommandInterface
{
    public function __construct(
        public readonly MailMessageDto $mailMessage,
        public readonly int $priority = 0,
    ) {
    }
}
