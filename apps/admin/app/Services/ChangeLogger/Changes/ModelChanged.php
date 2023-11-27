<?php

namespace App\Admin\Services\ChangeLogger\Changes;

use App\Admin\Services\ChangeLogger\EventTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class ModelChanged implements ChangesInterface
{
    public function __construct(private readonly Model $model) {}

    public function event(): EventTypeEnum
    {
        return EventTypeEnum::UPDATED;
    }

    public function payload(): array
    {
        return $this->model->getChanges();
    }

    public function entityClass(): ?string
    {
        return $this->model::class;
    }

    public function entityId(): string|int|null
    {
        return $this->model->id;
    }
}