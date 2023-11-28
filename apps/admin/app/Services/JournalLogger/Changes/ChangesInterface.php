<?php

namespace App\Admin\Services\JournalLogger\Changes;

use App\Admin\Services\JournalLogger\EventTypeEnum;

interface ChangesInterface
{
    public function event(): EventTypeEnum;

    public function entityClass(): ?string;

    public function entityId(): string|int|null;

    public function payload(): ?array;
}