<?php

namespace Sdk\Shared\Dto\Mail;

class MailMessageDto
{
    public function __construct(
        public readonly array $to,
        public readonly string $subject,
        public readonly string $body,
        public readonly array $from = [],
        public readonly array $replyTo = [],
        public readonly array $cc = [],
        public readonly array $bcc = [],
        public readonly array $tags = [],
        public readonly array $metadata = [],
        public readonly int $priority = 0
    ) {
    }
}
