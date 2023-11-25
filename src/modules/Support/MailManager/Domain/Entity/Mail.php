<?php

namespace Module\Support\MailManager\Domain\Entity;

use Module\Support\MailManager\Domain\ValueObject\AddressList;
use Module\Support\MailManager\Domain\ValueObject\Attachments;
use Module\Support\MailManager\Domain\ValueObject\MailBody;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;
use Sdk\Shared\Contracts\Support\SerializableInterface;

final class Mail implements SerializableInterface
{
    private AddressList $from;

    private AddressList $replyTo;

    private AddressList $cc;

    private AddressList $bcc;

    private Attachments $attachments;

    private array $tags = [];

    private array $metadata = [];

    public function __construct(
        private readonly MailId $id,
        private QueueMailStatusEnum $status,
        private AddressList $to,
        private string $subject,
        private MailBody $body,
    ) {
        $this->from = new AddressList();
        $this->replyTo = new AddressList();
        $this->cc = new AddressList();
        $this->bcc = new AddressList();
        $this->attachments = new Attachments();
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
            //'attachments' => $this->attachments,
        ];
    }

    public static function deserialize(array $payload): Mail
    {
        return new Mail(
            new MailId($payload['id']),
            QueueMailStatusEnum::from($payload['status']),
            AddressList::deserialize($payload['to']),
            $payload['subject'],
            new MailBody($payload['body']),
//            AddressList::deserialize($payload['from']),
//            AddressList::deserialize($payload['replyTo']),
//            AddressList::deserialize($payload['cc']),
//            AddressList::deserialize($payload['bcc']),
//            $payload['priority']
        );
    }
}