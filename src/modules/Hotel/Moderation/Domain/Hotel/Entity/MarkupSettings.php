<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\Entity;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\EarlyCheckInCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\LateCheckOutCollection;
use Module\Shared\Contracts\Domain\EntityInterface;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\Percent;

final class MarkupSettings implements EntityInterface, SerializableInterface
{
    public function __construct(
        private readonly HotelId $id,
        private Percent $vat,
        private Percent $touristTax,
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

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'vat' => $this->vat->value(),
            'touristTax' => $this->touristTax->value(),
            'earlyCheckIn' => $this->earlyCheckIn->serialize(),
            'lateCheckOut' => $this->lateCheckOut->serialize(),
            'cancelPeriods' => $this->cancelPeriods->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            id: new HotelId($payload['id']),
            vat: new Percent($payload['vat']),
            touristTax: new Percent($payload['touristTax']),
            earlyCheckIn: EarlyCheckInCollection::deserialize($payload['earlyCheckIn']),
            lateCheckOut: LateCheckOutCollection::deserialize($payload['lateCheckOut']),
            cancelPeriods: CancelPeriodCollection::deserialize($payload['cancelPeriods']),
        );
    }
}
