<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;

final class CancelFeeValue implements ValueObjectInterface, SerializableInterface
{
    public function __construct(
        private readonly FeeValue $value,
        private readonly CancelFeePeriodTypeEnum $cancelPeriodType
    ) {}

    public function value(): FeeValue
    {
        return $this->value;
    }

    public function cancelPeriodType(): CancelFeePeriodTypeEnum
    {
        return $this->cancelPeriodType;
    }

    public function serialize(): array
    {
        return [
            'value' => $this->value->serialize(),
            'cancelPeriodType' => $this->cancelPeriodType->value
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            FeeValue::deserialize($payload['value']),
            CancelFeePeriodTypeEnum::from($payload['cancelPeriodType'])
        );
    }
}
