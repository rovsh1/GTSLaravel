<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;
use Module\Shared\ValueObject\TimePeriod;

/**
 * @see \Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\Condition
 */
final class Condition implements ValueObjectInterface, SerializableDataInterface, CanEquate
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

    public function isEqual(mixed $b): bool
    {
        return $b instanceof Condition
            ? $this->priceMarkup->isEqual($b->priceMarkup) && $this->timePeriod->isEqual($b->timePeriod)
            : $this === $b;
    }
}
