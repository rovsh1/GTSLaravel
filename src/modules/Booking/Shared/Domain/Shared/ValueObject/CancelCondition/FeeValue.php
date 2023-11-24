<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Enum\Pricing\ValueTypeEnum;

class FeeValue implements SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'value' => $this->value,
            'type' => $this->type->value,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['value'],
            ValueTypeEnum::from($data['type']),
        );
    }
}
