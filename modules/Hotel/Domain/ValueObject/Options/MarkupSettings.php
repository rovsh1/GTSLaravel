<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class MarkupSettings implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private Percent $vat,
        private Percent $touristTax,
        private ClientMarkups $clientMarkups,
        private ?EarlyCheckInCollection $earlyCheckIn,
        private ?LateCheckOutCollection $lateCheckOut,
        private CancelPeriodCollection $cancelPeriods,
    ) {}

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

    public function earlyCheckIn(): ?EarlyCheckInCollection
    {
        return $this->earlyCheckIn;
    }

    public function lateCheckOut(): ?LateCheckOutCollection
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
            'vat' => $this->vat->value(),
            'touristTax' => $this->touristTax->value(),
            'clientMarkups' => $this->clientMarkups->toData(),
            'earlyCheckIn' => $this->earlyCheckIn?->toData(),
            'lateCheckOut' => $this->lateCheckOut?->toData(),
            'cancelPeriods' => $this->cancelPeriods->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        $earlyCheckIn = $data['earlyCheckIn'] ?? null;
        $lateCheckOut = $data['lateCheckOut'] ?? null;
        return new static(
            vat: new Percent($data['vat']),
            touristTax: new Percent($data['touristTax']),
            clientMarkups: ClientMarkups::fromData($data['clientMarkups']),
            earlyCheckIn: $earlyCheckIn !== null ? EarlyCheckInCollection::fromData($earlyCheckIn) : null,
            lateCheckOut: $lateCheckOut !== null ? LateCheckOutCollection::fromData($lateCheckOut) : null,
            cancelPeriods: CancelPeriodCollection::fromData($data['cancelPeriods'])
        );
    }
}
