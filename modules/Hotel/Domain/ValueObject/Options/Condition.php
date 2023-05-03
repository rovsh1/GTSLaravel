<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class Condition implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private TimePeriod $timePeriod,
        private Percent $priceMarkup
    ) {}

    public function timePeriod(): TimePeriod
    {
        return $this->timePeriod;
    }

    public function priceMarkup(): Percent
    {
        return $this->priceMarkup;
    }

    public function toData(): array
    {
        return [
            'timePeriod' => $this->timePeriod->toData(),
            'priceMarkup' => $this->priceMarkup->value()
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            TimePeriod::fromData($data['timePeriod']),
            new Percent($data['priceMarkup'])
        );
    }
}
