<?php

namespace Module\Services\MailManager\Application\Dto;

class MailTemplateDto
{
    public function __construct(
        public readonly string $from,
        public readonly array $replyTo = [],
        public readonly array $cc = [],
        public readonly array $bcc = [],
        public readonly array $tags = [],
        public readonly array $metadata = [],
        public readonly int $priority = 0
    ) {
    }
}
