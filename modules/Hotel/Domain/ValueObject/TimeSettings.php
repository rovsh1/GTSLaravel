<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\Time;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class TimeSettings implements ValueObjectInterface
{
    public function __construct(
        private Time $checkInAfter,
        private Time $checkOutBefore,
        private ?Time $breakfastAfter,
    ) {}

    public function checkInFrom(): Time
    {
        return $this->checkInAfter;
    }

    public function checkOutTo(): Time
    {
        return $this->checkOutBefore;
    }

    public function breakfastFrom(): ?Time
    {
        return $this->breakfastAfter;
    }
}
