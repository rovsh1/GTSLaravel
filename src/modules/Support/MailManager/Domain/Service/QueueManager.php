<?php

namespace Module\Support\MailManager\Domain\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Storage\QueueStorageInterface;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Throwable;

class QueueManager implements QueueManagerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly QueueStorageInterface $queueStorage,
    ) {
    }

    public function size(): int
    {
        return $this->queueStorage->waitingCount();
    }

    public function pop(): ?Mail
    {
        return $this->queueStorage->findWaiting();
    }

    public function sendSync(Mail $mailMessage, array $context = null): void
    {
        $this->queueStorage->push($mailMessage, 0, $context);

        $this->sendWaitingMessage($mailMessage);
    }

    public function push(Mail $mailMessage, int $priority = 0, array $context = null): void
    {
        $this->queueStorage->push($mailMessage, $priority, $context);
    }

    public function resendMessage(MailId $uuid): void
    {
        $message = $this->queueStorage->find($uuid);

        $this->sendWaitingMessage($message);
    }

    public function sendWaitingMessages(): void
    {
        while ($message = $this->queueStorage->findWaiting()) {
            $this->sendWaitingMessage($message);
        }
    }

    public function sendWaitingMessage(Mail $mailMessage): void
    {
        $mailMessage->setProcessingStatus();
        $this->queueStorage->store($mailMessage);

        try {
            $this->mailer->send($mailMessage);
        } catch (Throwable $exception) {
            $mailMessage->setFailedStatus($exception);
            $this->queueStorage->store($mailMessage);

            return;
        }

        $mailMessage->setSentStatus();
        $this->queueStorage->store($mailMessage);
    }

    public function retry(MailId $uuid): void
    {
        $this->queueStorage->retry($uuid);
    }

    public function retryAll(): void
    {
        $this->queueStorage->retryAll();
    }
}