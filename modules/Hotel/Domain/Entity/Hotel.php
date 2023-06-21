<?php

namespace Module\Hotel\Domain\Entity;

use Module\Hotel\Domain\ValueObject\TimeSettings;
use Module\Shared\Domain\ValueObject\Id;

class Hotel
{
    public function __construct(
        private readonly Id    $id,
        private string $name,
        private TimeSettings $timeSettings,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function timeSettings(): TimeSettings
    {
        return $this->timeSettings;
    }
}
