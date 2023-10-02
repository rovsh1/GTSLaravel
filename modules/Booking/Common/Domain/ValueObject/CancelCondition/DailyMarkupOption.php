<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\ValueObject\CancelCondition;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

/**
 * @see \Module\Hotel\Domain\ValueObject\MarkupSettings\DailyMarkupOption
 */
final class DailyMarkupOption implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly Percent $percent,
        private readonly CancelPeriodTypeEnum $cancelPeriodType,
        private readonly int $daysCount
    ) {}

    public function percent(): Percent
    {
        return $this->percent;
    }

    public function cancelPeriodType(): CancelPeriodTypeEnum
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
            'percent' => $this->percent->value(),
            'cancelPeriodType' => $this->cancelPeriodType->value,
            'daysCount' => $this->daysCount,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            new Percent($data['percent']),
            CancelPeriodTypeEnum::from($data['cancelPeriodType']),
            $data['daysCount'],
        );
    }
}
