<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;

final class DailyCancelFeeValue implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly FeeValue $value,
        private readonly CancelFeePeriodTypeEnum $cancelPeriodType,
        private readonly int $daysCount
    ) {}

    public function value(): FeeValue
    {
        return $this->value;
    }

    public function cancelPeriodType(): CancelFeePeriodTypeEnum
    {
        return $this->cancelPeriodType;
    }

    public function daysCount(): int
    {
        return $this->daysCount;
    }

    public function toData(): array
    {
        return [
            'value' => $this->value->toData(),
            'cancelPeriodType' => $this->cancelPeriodType->value,
            'daysCount' => $this->daysCount,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            FeeValue::fromData($data['value']),
            CancelFeePeriodTypeEnum::from($data['cancelPeriodType']),
            $data['daysCount'],
        );
    }
}
