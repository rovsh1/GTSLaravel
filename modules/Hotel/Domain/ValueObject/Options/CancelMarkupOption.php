<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

class CancelMarkupOption implements SerializableDataInterface
{
    public function __construct(
        private Percent $percent,
        private CancelPeriodTypeEnum $cancelPeriodType
    ) {}

    public function percent(): Percent
    {
        return $this->percent;
    }

    public function cancelPeriodType(): CancelPeriodTypeEnum
    {
        return $this->cancelPeriodType;
    }

    public function toData(): array
    {
        return [
            'percent' => $this->percent->value(),
            'cancelPeriodType' => $this->cancelPeriodType->value
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            new Percent($data['percent']),
            CancelPeriodTypeEnum::from($data['cancelPeriodType'])
        );
    }
}
