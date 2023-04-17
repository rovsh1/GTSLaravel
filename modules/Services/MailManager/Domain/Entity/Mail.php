<?php

namespace Module\Services\MailManager\Domain\Entity;

use Module\Services\MailManager\Domain\ValueObject\AddressList;
use Module\Services\MailManager\Domain\ValueObject\Attachments;
use Module\Services\MailManager\Domain\ValueObject\MailBody;

class Mail
{
    private readonly AddressList $from;

    private readonly AddressList $replyTo;

    private readonly AddressList $cc;

    private readonly AddressList $bcc;

    private readonly Attachments $attachments;

    private array $tags = [];

    private array $metadata = [];

    private int $priority = 0;

    public function __construct(
        private readonly AddressList $to,
        private string $subject,
        private readonly MailBody $body,
    ) {
        $this->from = new AddressList();
        $this->replyTo = new AddressList();
        $this->cc = new AddressList();
        $this->bcc = new AddressList();
        $this->attachments = new Attachments();
    }

    public function to(): AddressList
    {
        return $this->to;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function body(): MailBody
    {
        return $this->body;
    }

    public function attachments(): Attachments
    {
        return $this->attachments;
    }

    public function from(): AddressList
    {
        return $this->from;
    }

    public function replyTo(): AddressList
    {
        return $this->replyTo;
    }

    public function cc(): AddressList
    {
        return $this->cc;
    }

    public function bcc(): AddressList
    {
        return $this->bcc;
    }

    public function priority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function serialize(): string
    {
        return json_encode([
            'to' => $this->to->toArray(),
            'subject' => $this->subject,
            'body' => $this->body->value(),
            'from' => $this->from->toArray(),
            'replyTo' => $this->replyTo->toArray(),
            'cc' => $this->cc->toArray(),
            'bcc' => $this->bcc->toArray(),
            //'attachments' => $this->attachments,
            'priority' => $this->priority,
        ]);
    }
}