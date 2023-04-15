<?php

namespace Module\Services\MailManager\Domain\Service;

use Module\Services\MailManager\Domain\Entity\Mail;
use Module\Services\MailManager\Domain\Entity\QueueMessage;
use Module\Services\MailManager\Domain\Factory\MailMessageFactory;
use Module\Services\MailManager\Domain\Repository\QueueRepositoryInterface;
use Module\Services\MailManager\Domain\ValueObject\QueueMailStatusEnum;
use Throwable;

class QueueManager implements QueueManagerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly QueueRepositoryInterface $queueRepository,
    ) {
    }

    public function sendSync(Mail $mailMessage): QueueMessage
    {
        return $this->_send(
            $this->queueRepository->push(
                $mailMessage->serialize(),
                0,
                QueueMailStatusEnum::PROCESSING
            )
        );
    }

    public function push(Mail $mailMessage, int $priority = 0): QueueMessage
    {
        return $this->queueRepository->push(
            $mailMessage->serialize(),
            $priority,
            QueueMailStatusEnum::WAITING
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
//        $this->_send($queueMessage);
//    }

    public function sendWaitingMessages(): void
    {
        while ($message = $this->queueRepository->findWaiting()) {
            $this->_send($message);
        }
    }

    public function retry(QueueMessage $message): void
    {
        $this->queueRepository->retry($message->uuid);
    }

    public function retryAll(): void
    {
        $this->queueRepository->retryAll();
    }

    private function _send(QueueMessage $queueMessage): QueueMessage
    {
        $this->queueRepository->updateStatus($queueMessage->uuid, QueueMailStatusEnum::PROCESSING);

        $mailMessage = MailMessageFactory::deserialize($queueMessage->payload);

        try {
            $this->mailer->send($mailMessage);
        } catch (Throwable $exception) {
            $this->queueRepository->updateStatus($queueMessage->uuid, QueueMailStatusEnum::FAILED);

            return $queueMessage;
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

        return $queueMessage;
    }
}