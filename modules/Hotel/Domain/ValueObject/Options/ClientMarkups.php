<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ClientMarkups implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private Percent $individual,
        private Percent $TA,
        private Percent $OTA,
        private Percent $TO,
    ) {}

    public function individual(): Percent
    {
        return $this->individual;
    }

    public function TA(): Percent
    {
        return $this->TA;
    }

    public function OTA(): Percent
    {
        return $this->OTA;
    }

    public function TO(): Percent
    {
        return $this->TO;
    }

    public function toData(): array
    {
        return [
            'individual' => $this->individual->value(),
            'TA' => $this->TA->value(),
            'OTA' => $this->OTA->value(),
            'TO' => $this->TO->value(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            new Percent($data['individual']),
            new Percent($data['TA']),
            new Percent($data['OTA']),
            new Percent($data['TO']),
        );
    }
}
