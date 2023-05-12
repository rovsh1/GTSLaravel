<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\MarkupSettings;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class ClientMarkups implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly Percent $individual,
        private readonly Percent $TA,
        private readonly Percent $OTA,
        private readonly Percent $TO,
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
