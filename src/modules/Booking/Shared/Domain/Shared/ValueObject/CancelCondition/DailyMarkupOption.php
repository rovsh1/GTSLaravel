<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;

/**
 * @see \Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupOption
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
