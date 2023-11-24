<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;
use Module\Shared\ValueObject\Percent;
use Module\Shared\ValueObject\TimePeriod;

final class Condition implements ValueObjectInterface, SerializableInterface
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

    public function setTimePeriod(TimePeriod $timePeriod): void
    {
        $this->timePeriod = $timePeriod;
    }

    public function setPriceMarkup(Percent $priceMarkup): void
    {
        $this->priceMarkup = $priceMarkup;
    }

    public function serialize(): array
    {
        return [
            'timePeriod' => $this->timePeriod->serialize(),
            'priceMarkup' => $this->priceMarkup->value()
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            TimePeriod::deserialize($payload['timePeriod']),
            new Percent($payload['priceMarkup'])
        );
    }
}
