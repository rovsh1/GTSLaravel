<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class MarkupSettings implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly Percent $vat,
        private readonly Percent $touristTax,
        private readonly ClientMarkups $clientMarkups,
        private readonly EarlyCheckInCollection $earlyCheckIn,
        private readonly LateCheckOutCollection $lateCheckOut,
        private readonly CancelPeriodCollection $cancelPeriods,
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
            vat: new Percent($data['vat']),
            touristTax: new Percent($data['touristTax']),
            clientMarkups: ClientMarkups::fromData($data['clientMarkups']),
            earlyCheckIn: EarlyCheckInCollection::fromData($data['earlyCheckIn']),
            lateCheckOut: LateCheckOutCollection::fromData($data['lateCheckOut']),
            cancelPeriods: CancelPeriodCollection::fromData($data['cancelPeriods'])
        );
    }
}
