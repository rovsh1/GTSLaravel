<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Entity;

use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriodCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\ClientMarkups;
use Module\Hotel\Domain\ValueObject\MarkupSettings\EarlyCheckInCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\LateCheckOutCollection;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

class MarkupSettings implements EntityInterface, SerializableDataInterface
{
    public function __construct(
        private readonly int $id,
        private readonly Percent $vat,
        private readonly Percent $touristTax,
        private readonly ClientMarkups $clientMarkups,
        private readonly EarlyCheckInCollection $earlyCheckIn,
        private readonly LateCheckOutCollection $lateCheckOut,
        private readonly CancelPeriodCollection $cancelPeriods,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function vat(): Percent
    {
        return $this->vat;
    }

    public function touristTax(): Percent
    {
        return $this->touristTax;
    }

    public function clientMarkups(): ClientMarkups
    {
        return $this->clientMarkups;
    }

    public function earlyCheckIn(): EarlyCheckInCollection
    {
        return $this->earlyCheckIn;
    }

    public function lateCheckOut(): LateCheckOutCollection
    {
        return $this->lateCheckOut;
    }

    public function cancelPeriods(): CancelPeriodCollection
    {
        return $this->cancelPeriods;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'vat' => $this->vat->value(),
            'touristTax' => $this->touristTax->value(),
            'clientMarkups' => $this->clientMarkups->toData(),
            'earlyCheckIn' => $this->earlyCheckIn->toData(),
            'lateCheckOut' => $this->lateCheckOut->toData(),
            'cancelPeriods' => $this->cancelPeriods->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            id: $data['id'],
            vat: new Percent($data['vat']),
            touristTax: new Percent($data['touristTax']),
            clientMarkups: ClientMarkups::fromData($data['clientMarkups']),
            earlyCheckIn: EarlyCheckInCollection::fromData($data['earlyCheckIn']),
            lateCheckOut: LateCheckOutCollection::fromData($data['lateCheckOut']),
            cancelPeriods: CancelPeriodCollection::fromData($data['cancelPeriods'])
        );
    }
}