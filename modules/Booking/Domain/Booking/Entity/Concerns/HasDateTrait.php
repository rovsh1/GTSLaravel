<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

use DateTimeInterface;

trait HasDateTrait
{
    public function date(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }
}