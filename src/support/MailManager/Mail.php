<?php

namespace Support\MailManager;

use Sdk\Shared\Contracts\Support\SerializableInterface;
use Support\MailManager\ValueObject\AddressList;
use Support\MailManager\ValueObject\Attachments;
use Support\MailManager\ValueObject\MailBody;
use Support\MailManager\ValueObject\MailId;
use Support\MailManager\ValueObject\QueueMailStatusEnum;

final class Mail implements SerializableInterface
{
    private AddressList $from;

    private AddressList $replyTo;

    private AddressList $cc;

    private AddressList $bcc;

    private readonly Attachments $attachments;

    private array $tags = [];

    private array $metadata = [];

    public function __construct(
        private readonly MailId $id,
        private QueueMailStatusEnum $status,
        private readonly AddressList $to,
        private string $subject,
        private readonly MailBody $body,
        Attachments $attachments = null,
    ) {
        $this->from = new AddressList();
        $this->replyTo = new AddressList();
        $this->cc = new AddressList();
        $this->bcc = new AddressList();
        $this->attachments = $attachments ?? new Attachments([]);
    }

    public function id(): MailId
    {
        return $this->id;
    }

    public function status(): QueueMailStatusEnum
    {
        return $this->status;
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

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function setProcessingStatus(): void
    {
        $this->status = QueueMailStatusEnum::PROCESSING;
    }

    public function setFailedStatus(\Throwable $exception): void
    {
        $this->status = QueueMailStatusEnum::FAILED;
    }

    public function setSentStatus(): void
    {
        $this->status = QueueMailStatusEnum::SENT;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'status' => $this->status->value,
            'to' => $this->to->serialize(),
            'subject' => $this->subject,
            'body' => $this->body->value(),
            'from' => $this->from->serialize(),
            'replyTo' => $this->replyTo->serialize(),
            'cc' => $this->cc->serialize(),
            'bcc' => $this->bcc->serialize(),
            'attachments' => $this->attachments->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new Mail(
            new MailId($payload['id']),
            QueueMailStatusEnum::from($payload['status']),
            AddressList::deserialize($payload['to']),
            $payload['subject'],
            new MailBody($payload['body']),
            Attachments::deserialize($payload['attachments'])
//            AddressList::deserialize($payload['from']),
//            AddressList::deserialize($payload['replyTo']),
//            AddressList::deserialize($payload['cc']),
//            AddressList::deserialize($payload['bcc']),
//            $payload['priority']
        );
    }
}