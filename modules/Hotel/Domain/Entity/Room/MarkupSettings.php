<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Entity\Room;

use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class MarkupSettings implements EntityInterface, SerializableDataInterface
{
    public function __construct(
        private readonly int $id,
        private Percent $individual,
        private Percent $TA,
        private Percent $OTA,
        private Percent $TO,
        private Percent $discount,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

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

    public function discount(): Percent
    {
        return $this->discount;
    }

    public function setIndividual(Percent $individual): void
    {
        $this->individual = $individual;
    }

    public function setTA(Percent $TA): void
    {
        $this->TA = $TA;
    }

    public function setOTA(Percent $OTA): void
    {
        $this->OTA = $OTA;
    }

    public function setTO(Percent $TO): void
    {
        $this->TO = $TO;
    }

    public function setDiscount(Percent $discount): void
    {
        $this->discount = $discount;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'individual' => $this->individual()->value(),
            'TA' => $this->TA()->value(),
            'OTA' => $this->OTA()->value(),
            'TO' => $this->TO()->value(),
            'discount' => $this->discount->value(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            id: $data['id'],
            individual: new Percent($data['individual']),
            TA: new Percent($data['TA']),
            OTA: new Percent($data['OTA']),
            TO: new Percent($data['TO']),
            discount: new Percent($data['discount']),
        );
    }
}
