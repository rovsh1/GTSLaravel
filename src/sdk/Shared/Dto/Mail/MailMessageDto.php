<?php

namespace Sdk\Shared\Dto\Mail;

class MailMessageDto
{
    /**
     * @param string[] $to
     * @param string $subject
     * @param string $body
     * @param AttachmentDto[] $attachments
     * @param string[] $from
     * @param string[] $replyTo
     * @param string[] $cc
     * @param string[] $bcc
     * @param string[] $tags
     * @param string[] $metadata
     * @param int $priority
     */
    public function __construct(
        public readonly array $to,
        public readonly string $subject,
        public readonly string $body,
        public readonly array $attachments = [],
        public readonly array $from = [],
        public readonly array $replyTo = [],
        public readonly array $cc = [],
        public readonly array $bcc = [],
        public readonly array $tags = [],
        public readonly array $metadata = [],
        public readonly int $priority = 0
    ) {}
}
