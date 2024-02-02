<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\TimeSettings\BreakfastPeriod;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\Time;

class TimeSettings implements SerializableInterface
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

    public function serialize(): array
    {
        return [
            'checkInAfter' => $this->checkInAfter->value(),
            'checkOutBefore' => $this->checkOutBefore->value(),
            'breakfastPeriod' => $this->breakfastPeriod?->serialize()
        ];
    }

    public static function deserialize(array $payload): static
    {
        $breakfastPeriod = $payload['breakfastPeriod'] ?? null;

        return new static(
            new Time($payload['checkInAfter'] ?? '14:00'),
            new Time($payload['checkOutBefore'] ?? '12:00'),
            $breakfastPeriod !== null ? BreakfastPeriod::deserialize($payload['breakfastPeriod']) : null
        );
    }
}
