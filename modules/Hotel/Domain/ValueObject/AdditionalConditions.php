<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject;

class AdditionalConditions
{
    public function __construct(
        private string $startTime,
        private string $endTime,
        private ConditionTypeEnum $type,
        private int $priceMarkup
    ) {}

    public function startTime(): string
    {
        return $this->startTime;
    }

    public function endTime(): string
    {
        return $this->endTime;
    }

    public function type(): ConditionTypeEnum
    {
        return $this->type;
    }

    public function priceMarkup(): int
    {
        return $this->priceMarkup;
    }
}
