<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject;

use Module\Hotel\Domain\ValueObject\TimeSettings\BreakfastPeriod;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\Time;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class TimeSettings implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private Time $checkInAfter,
        private Time $checkOutBefore,
        private ?BreakfastPeriod $breakfastPeriod
    ) {}

    public function checkInAfter(): Time
    {
        return $this->checkInAfter;
    }

    public function checkOutBefore(): Time
    {
        return $this->checkOutBefore;
    }

    public function breakfastPeriod(): ?BreakfastPeriod
    {
        return $this->breakfastPeriod;
    }

    public function toData(): array
    {
        return [
            'checkInAfter' => $this->checkInAfter->value(),
            'checkOutBefore' => $this->checkOutBefore->value(),
            'breakfastPeriod' => $this->breakfastPeriod?->toData()
        ];
    }

    public static function fromData(array $data): static
    {
        $breakfastPeriod = $data['breakfastPeriod'] ?? null;

        return new static(
            new Time($data['checkInAfter']),
            new Time($data['checkOutBefore']),
            $breakfastPeriod !== null ? BreakfastPeriod::fromData($data['breakfastPeriod']) : null
        );
    }
}