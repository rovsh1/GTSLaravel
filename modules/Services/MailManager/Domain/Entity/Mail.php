<?php

namespace Module\Services\MailManager\Domain\Entity;

use Module\Services\MailManager\Domain\ValueObject\AddressList;
use Module\Services\MailManager\Domain\ValueObject\MailBody;

class Mail
{
    private array $from = [];

    private array $replyTo = [];

    private array $cc = [];

    private array $bcc = [];

    private array $attachments = [];

    private array $tags = [];

    private array $metadata = [];

    private int $priority = 0;

    public function __construct(
        private AddressList $to,
        private string $subject,
        private MailBody $body,
    ) {
    }

    public function to(): AddressList
    {
        return $this->to;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function body(): MailBody
    {
        return $this->body;
    }

    public function serialize(): string
    {
        return '';
    }
}