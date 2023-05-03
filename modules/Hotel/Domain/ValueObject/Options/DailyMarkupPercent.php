<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

class DailyMarkupPercent extends CancelMarkupPercent
{
    public function __construct(
        int $value,
        CancelPeriodTypeEnum $cancelPeriodType,
        private int $daysCount
    ) {
        parent::__construct($value, $cancelPeriodType);
    }

    public function daysCount(): int
    {
        return $this->daysCount;
    }

    public function toData(): array
    {
        return [
            'value' => $this->value,
            'cancelPeriodType' => $this->cancelPeriodType()->value,
            'daysCount' => $this->daysCount,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['value'],
            CancelPeriodTypeEnum::from($data['cancelPeriodType']),
            $data['daysCount'],
        );
    }
}
