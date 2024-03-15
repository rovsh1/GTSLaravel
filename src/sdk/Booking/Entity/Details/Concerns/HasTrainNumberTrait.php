<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\Event\DetailsFieldUpdated;

trait HasTrainNumberTrait
{
    public function trainNumber(): ?string
    {
        return $this->trainNumber;
    }

    public function setTrainNumber(?string $trainNumber): void
    {
        $valueBefore = $this->trainNumber;
        $this->trainNumber = $trainNumber;
        $this->pushEvent(new DetailsFieldUpdated($this, 'trainNumber', $trainNumber, $valueBefore));
    }
}
