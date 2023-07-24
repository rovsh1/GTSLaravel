<?php

namespace Module\Support\MailManager\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Support\MailManager\Domain\Entity\QueueMessage;
use Module\Support\MailManager\Domain\Repository\QueueRepositoryInterface;
use Module\Support\MailManager\Domain\ValueObject\QueueMailStatusEnum;
use Module\Support\MailManager\Infrastructure\Model\QueueMessage as Model;

class QueueRepository implements QueueRepositoryInterface
{
    public const EXPIRED_DATES = '-14 days';
    public const MAX_ATTEMPTS  = 3;

    public function find(string $uuid): ?QueueMessage
    {
        return $this->entityFromModel(Model::find($uuid));
    }

    public function push(
        string $subject,
        string $payload,
        int $priority = 0,
        QueueMailStatusEnum $status = QueueMailStatusEnum::WAITING,
        array $context = null
    ): QueueMessage {
        $model = Model::create([
            'subject' => $subject,
            'payload' => $payload,
            'priority' => $priority,
            'status' => $status->value,
            'context' => $context
        ]);

        return $this->entityFromModel($model);
    }

    public function retry(string $uuid): void
    {
        $model = $this->findModel($uuid);

        $model->update([
            'status' => QueueMailStatusEnum::WAITING->value,
            'failed_at' => null,
            'attempts' => $model->attempts + 1
        ]);
    }

    public function retryAll(): void
    {
        Model::where('status', QueueMailStatusEnum::FAILED->value)
            ->where('attempts', '<', self::MAX_ATTEMPTS)
            ->update([
                'status' => QueueMailStatusEnum::WAITING->value,
                'failed_at' => null,
                'attempts' => DB::raw('attempts+1')
            ]);
    }

    public function updateStatus(string $uuid, QueueMailStatusEnum $status): void
    {
        $model = $this->findModel($uuid);
        $updateData = [
            'status' => $status->value,
        ];

        switch ($status) {
            case QueueMailStatusEnum::FAILED:
                $updateData['failed_at'] = now();
                break;
            case QueueMailStatusEnum::SENT:
                $updateData['sent_at'] = now();
                break;
        }

        $model->update($updateData);
    }

    public function findWaiting(): ?QueueMessage
    {
        $model = Model::where('status', QueueMailStatusEnum::WAITING->value)->first();

        return $this->entityFromModel($model);
    }

    public function clearExpired(\DateTime $date = null): void
    {
        if (null === $date) {
            $date = now();
            $date->modify(self::EXPIRED_DATES);
        }

        Model::where('date', '<', $date)
            ->delete();
    }

    private function findModel(string $uuid): Model
    {
        $model = Model::find($uuid);
        if (!$model) {
            throw new \Exception('Queue message not found');
        }

        return $model;
    }

    private function entityFromModel(Model|null $model): ?QueueMessage
    {
        if (!$model) {
            return null;
        }

        return new QueueMessage(
            $model->uuid,
            $model->payload,
            QueueMailStatusEnum::from($model->status)
        );
    }

    public function waitingCount(): int
    {
        return Model::where('status', QueueMailStatusEnum::WAITING->value)->count();
    }
}