<?php

namespace Module\Support\MailManager\Domain\Service;

use Illuminate\Support\Facades\Log;
use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Storage\QueueStorageInterface;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Throwable;

class MailManager implements MailManagerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly QueueStorageInterface $queueStorage,
    ) {}

    public function sendSync(Mail $mailMessage): void
    {
        $this->queueStorage->push($mailMessage, 0);

        $this->sendWaitingMessage($mailMessage);
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
            throw $exception;
        }

        $mailMessage->setSentStatus();
        $this->queueStorage->store($mailMessage);
    }

    public function resendMessage(MailId $uuid): void
    {
        $message = $this->queueStorage->find($uuid);

        $this->sendWaitingMessage($message);
    }
}