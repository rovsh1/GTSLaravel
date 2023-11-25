<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

use Module\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Enum\Pricing\ValueTypeEnum;

class FeeValue implements SerializableInterface
{
    public function __construct(
        private readonly int $value,
        private readonly ValueTypeEnum $type,
    ) {}

    public static function createPercent(int $value): static
    {
        return new static($value, ValueTypeEnum::PERCENT);
    }

    public static function createAbsolute(int $value): static
    {
        return new static($value, ValueTypeEnum::ABSOLUTE);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function type(): ValueTypeEnum
    {
        return $this->type;
    }

    public function serialize(): array
    {
        return [
            'value' => $this->value,
            'type' => $this->type->value,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            $payload['value'],
            ValueTypeEnum::from($payload['type']),
        );
    }
}
