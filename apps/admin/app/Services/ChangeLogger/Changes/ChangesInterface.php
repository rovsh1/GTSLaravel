<?php

namespace App\Admin\Services\ChangeLogger\Changes;

use App\Admin\Services\ChangeLogger\EventTypeEnum;

interface ChangesInterface
{
    public function event(): EventTypeEnum;

    public function entityClass(): ?string;

    public function entityId(): string|int|null;

    public function payload(): ?array;
}