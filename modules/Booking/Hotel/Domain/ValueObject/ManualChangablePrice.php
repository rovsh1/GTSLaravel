<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class ManualChangablePrice implements ValueObjectInterface, SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly float $value,
        private readonly bool $isManual = false
    ) {
    }

    public function value(): float
    {
        return $this->value;
    }

    public function isManual(): bool
    {
        return $this->isManual;
    }

    public function toData(): array
    {
        return [
            'value' => $this->value,
            'isManual' => $this->isManual
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['value'],
            $data['isManual'],
        );
    }

    /**
     * @param static $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        return $this->isManual === $b->isManual
            && $this->value === $b->value;
    }
}
