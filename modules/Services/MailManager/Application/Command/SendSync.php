<?php

namespace Module\Services\MailManager\Application\Command;

use Module\Services\MailManager\Application\Dto\MailMessageDto;
use Sdk\Module\Contracts\Bus\CommandInterface;

class SendSync implements CommandInterface
{
    public function __construct(
        public readonly MailMessageDto $mailMessage,
        public readonly ?array $context = null,
    ) {
    }
}
