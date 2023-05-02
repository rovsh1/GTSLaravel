<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ClientMarkups implements ValueObjectInterface
{
    public function __construct(
        private Percent $individual,
        private Percent $TA,
        private Percent $OTA,
        private Percent $OT,
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

    public function OT(): Percent
    {
        return $this->OT;
    }
}
