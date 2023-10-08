<?php

declare(strict_types=1);

namespace Module\Catalog\Domain\Hotel\Entity;

use Module\Catalog\Domain\Hotel\ValueObject\HotelId;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodCollection;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\ClientMarkups;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\EarlyCheckInCollection;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\LateCheckOutCollection;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;

final class MarkupSettings implements EntityInterface, SerializableDataInterface
{
    public function __construct(
        private readonly HotelId $id,
        private Percent $vat,
        private Percent $touristTax,
        private readonly ClientMarkups $clientMarkups,
        private readonly EarlyCheckInCollection $earlyCheckIn,
        private readonly LateCheckOutCollection $lateCheckOut,
        private readonly CancelPeriodCollection $cancelPeriods,
    ) {}

    public function id(): HotelId
    {
        return $this->id;
    }

    public function vat(): Percent
    {
        return $this->vat;
    }

    public function setVat(int $vat): void
    {
        $this->vat = new Percent($vat);
    }

    public function touristTax(): Percent
    {
        return $this->touristTax;
    }

    public function setTouristTax(int $touristTax): void
    {
        $this->touristTax = new Percent($touristTax);
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
            'id' => $this->id->value(),
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
            id: new HotelId($data['id']),
            vat: new Percent($data['vat']),
            touristTax: new Percent($data['touristTax']),
            clientMarkups: ClientMarkups::fromData($data['clientMarkups']),
            earlyCheckIn: EarlyCheckInCollection::fromData($data['earlyCheckIn']),
            lateCheckOut: LateCheckOutCollection::fromData($data['lateCheckOut']),
            cancelPeriods: CancelPeriodCollection::fromData($data['cancelPeriods']),
        );
    }
}
