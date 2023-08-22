<?php

namespace Module\Support\MailManager\Domain\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Repository\QueueRepositoryInterface;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Throwable;

class QueueManager implements QueueManagerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly QueueRepositoryInterface $queueRepository,
    ) {
    }

    public function size(): int
    {
        return $this->queueRepository->waitingCount();
    }

    public function pop(): ?Mail
    {
        return $this->queueRepository->findWaiting();
    }

    public function sendSync(Mail $mailMessage, array $context = null): void
    {
        $this->queueRepository->push($mailMessage, 0, $context);

        $this->sendWaitingMessage($mailMessage);
    }

    public function push(Mail $mailMessage, int $priority = 0, array $context = null): void
    {
        $this->queueRepository->push($mailMessage, $priority, $context);
    }

    public function resendMessage(MailId $uuid): void
    {
        $message = $this->queueRepository->find($uuid);

        $this->sendWaitingMessage($message);
    }

    public function sendWaitingMessages(): void
    {
        while ($message = $this->queueRepository->findWaiting()) {
            $this->sendWaitingMessage($message);
        }
    }

    public function sendWaitingMessage(Mail $mailMessage): void
    {
        $mailMessage->setProcessingStatus();
        $this->queueRepository->store($mailMessage);

        try {
            $this->mailer->send($mailMessage);
        } catch (Throwable $exception) {
            $mailMessage->setFailedStatus($exception);
            $this->queueRepository->store($mailMessage);

            return;
        }

        $mailMessage->setSentStatus();
        $this->queueRepository->store($mailMessage);
    }

    public function retry(MailId $uuid): void
    {
        $this->queueRepository->retry($uuid);
    }

    public function retryAll(): void
    {
        $this->queueRepository->retryAll();
    }
}