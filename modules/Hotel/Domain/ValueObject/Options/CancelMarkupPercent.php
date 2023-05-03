<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

class CancelMarkupPercent extends Percent implements SerializableDataInterface
{
    public function __construct(
        int $value,
        private CancelPeriodTypeEnum $cancelPeriodType
    ) {
        parent::__construct($value);
    }

    public function cancelPeriodType(): CancelPeriodTypeEnum
    {
        return $this->cancelPeriodType;
    }

    public function toData(): array
    {
        return [
            'value' => $this->value,
            'cancelPeriodType' => $this->cancelPeriodType->value
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['value'],
            CancelPeriodTypeEnum::from($data['cancelPeriodType'])
        );
    }
}
