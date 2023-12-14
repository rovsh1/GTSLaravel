<?php

namespace Module\Support\MailManager\Infrastructure\Storage;

use Illuminate\Support\Facades\DB;
use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Storage\QueueStorageInterface;
use Module\Support\MailManager\Domain\ValueObject\MailId;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;
use Module\Support\MailManager\Infrastructure\Model\QueueMessage as Model;
use Sdk\Shared\Contracts\Service\ApplicationContextInterface;

class QueueStorage implements QueueStorageInterface
{
    public const EXPIRED_DATES = '-14 days';
    public const MAX_ATTEMPTS  = 3;

    public function __construct(
        private readonly ApplicationContextInterface $applicationContext
    ) {
    }

    public function find(MailId $uuid): ?Mail
    {
        return $this->entityFromModel(Model::find($uuid));
    }

    public function push(Mail $mail, int $priority = 0, array $context = null): void
    {
        Model::create([
            'uuid' => $mail->id()->value(),
            'subject' => $mail->subject(),
            'payload' => $mail->serialize(),
            'priority' => $priority,
            'status' => $mail->status()->value,
            'context' => $this->applicationContext->toArray()
        ]);
    }

    public function retry(MailId $uuid): void
    {
        $model = $this->findModel($uuid->value());

        $model->update([
            'status' => QueueMailStatusEnum::WAITING->value,
            'failed_at' => null,
            'attempts' => $model->attempts + 1
        ]);
    }

    public function retryAll(): void
    {
        Model::where('status', QueueMailStatusEnum::FAILED->value)
//            ->where('attempts', '<', self::MAX_ATTEMPTS)
            ->update([
                'status' => QueueMailStatusEnum::WAITING->value,
                'failed_at' => null,
                'attempts' => DB::raw('attempts+1')
            ]);
    }

    public function store(Mail $mail): void
    {
        $model = $this->findModel($mail->id()->value());
        $updateData = [
            'status' => $mail->status()->value,
        ];

        switch ($mail->status()) {
            case QueueMailStatusEnum::FAILED:
                $updateData['failed_at'] = now();
                break;
            case QueueMailStatusEnum::SENT:
                $updateData['sent_at'] = now();
                break;
        }

        $model->update($updateData);
    }

    public function findWaiting(): ?Mail
    {
        $model = Model::where('status', QueueMailStatusEnum::WAITING->value)->first();

        return $this->entityFromModel($model);
    }

    public function clearExpired(\DateTimeInterface $date = null): void
    {
        if (null === $date) {
            $date = now();
            $date->modify(self::EXPIRED_DATES);
        }

        Model::where('date', '<', $date)
            ->delete();
    }

    public function waitingCount(): int
    {
        return Model::where('status', QueueMailStatusEnum::WAITING->value)->count();
    }

    private function findModel(string $uuid): Model
    {
        $model = Model::find($uuid);
        if (!$model) {
            throw new \Exception('Queue message not found');
        }

        return $model;
    }

    private function entityFromModel(Model|null $model): ?Mail
    {
        if (!$model) {
            return null;
        }

        return Mail::deserialize($model->payload);
    }
}