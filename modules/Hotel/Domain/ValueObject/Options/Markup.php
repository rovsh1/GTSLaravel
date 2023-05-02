<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class Markup implements ValueObjectInterface
{
    public function __construct(
        private Percent $vat,
        private Percent $tourCharge,
        private ClientMarkups $clientMarkups,
        private EarlyCheckInCollection $earlyCheckIn,
        private LateCheckOutCollection $lateCheckOut,
        private CancelPeriodCollection $cancelPeriods,
    ) {}

    public function vat(): Percent
    {
        return $this->vat;
    }

    public function tourCharge(): Percent
    {
        return $this->tourCharge;
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
}
