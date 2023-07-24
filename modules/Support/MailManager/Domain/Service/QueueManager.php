<?php

namespace Module\Support\MailManager\Domain\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Entity\QueueMessage;
use Module\Support\MailManager\Domain\Factory\MailMessageFactory;
use Module\Support\MailManager\Domain\Repository\QueueRepositoryInterface;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;
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

    public function pop(): ?QueueMessage
    {
        return $this->queueRepository->findWaiting();
    }

    public function sendSync(Mail $mailMessage, array $context = null): QueueMessage
    {
        $queueMessage = $this->queueRepository->push(
            $mailMessage->subject(),
            $mailMessage->serialize(),
            0,
            QueueMailStatusEnum::PROCESSING,
            $context
        );

        $this->sendWaitingMessage($queueMessage);

        return $queueMessage;
    }

    public function push(Mail $mailMessage, int $priority = 0, array $context = null): QueueMessage
    {
        return $this->queueRepository->push(
            $mailMessage->subject(),
            $mailMessage->serialize(),
            $priority,
            QueueMailStatusEnum::WAITING,
            $context
        );
    }

//    public function send(QueueMessage $queueMessage): void
//    {
//        if ($queueMessage->isWaiting()) {
//            throw new \Exception('Cant send not new message');
//        }
//
//        $this->queueRepository->updateStatus($queueMessage->uuid, QueueMailStatusEnum::PROCESSING);
//
//        $this->sendWaitingMessage($queueMessage);
//    }

    public function sendWaitingMessages(): void
    {
        while ($message = $this->queueRepository->findWaiting()) {
            $this->sendWaitingMessage($message);
        }
    }

    public function sendWaitingMessage(QueueMessage $queueMessage): void
    {
        $this->queueRepository->updateStatus($queueMessage->uuid, QueueMailStatusEnum::PROCESSING);

        $mailMessage = MailMessageFactory::deserialize($queueMessage->payload);

        try {
            $this->mailer->send($mailMessage);
        } catch (Throwable $exception) {
            $this->queueRepository->updateStatus($queueMessage->uuid, QueueMailStatusEnum::FAILED);

            return;
//            throw $exception;
        }

//        $queueCommand = $queueRecord->command();
//        try {
//            $this->commandBus->execute($queueCommand);
//        } catch (\Throwable $exception) {
//            //$preProcessor->fail($queueMessage, $mailMessage, $exception);
//            continue;
//        }

        $this->queueRepository->updateStatus($queueMessage->uuid, QueueMailStatusEnum::SENT);
    }

    public function retry(QueueMessage $message): void
    {
        $this->queueRepository->retry($message->uuid);
    }

    public function retryAll(): void
    {
        $this->queueRepository->retryAll();
    }
}