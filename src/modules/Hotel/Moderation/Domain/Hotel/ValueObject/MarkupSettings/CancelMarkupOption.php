<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;

final class CancelMarkupOption implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private Percent $percent,
        private readonly CancelPeriodTypeEnum $cancelPeriodType
    ) {}

    public function percent(): Percent
    {
        return $this->percent;
    }

    public function setPercent(Percent $percent): void
    {
        $this->percent = $percent;
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
