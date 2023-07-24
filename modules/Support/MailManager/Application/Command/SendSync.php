<?php

namespace Module\Support\MailManager\Application\Command;

use Module\Support\MailManager\Application\Dto\MailMessageDto;
use Sdk\Module\Contracts\Bus\CommandInterface;

class SendSync implements CommandInterface
{
    public function __construct(
        public readonly MailMessageDto $mailMessage,
        public readonly ?array $context = null,
    ) {
    }
}
