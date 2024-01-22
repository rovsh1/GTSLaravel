<?php

namespace App\Admin\Services\JournalLogger\Changes;

use App\Admin\Services\JournalLogger\EventTypeEnum;
use Illuminate\Database\Eloquent\Model;

class ModelDeleted implements ChangesInterface
{
    public function __construct(private readonly Model $model) {}

    public function event(): EventTypeEnum
    {
        return EventTypeEnum::DELETED;
    }

    public function payload(): array
    {
        return $this->model->toArray();
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
