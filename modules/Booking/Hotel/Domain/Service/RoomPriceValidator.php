<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Service;

use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\ValueObject\ManualChangablePrice;

class RoomPriceValidator
{
    public function __construct(private readonly Booking $booking) {}

    public function checkCanChangeHoPrice(ManualChangablePrice $price): void
    {
        if ($price->isManual() && $this->booking->price()->hoValue()->isManual()) {
            throw new \Exception('Нельзя');
        }
    }

    public function checkCanChangeBoPrice(ManualChangablePrice $price): void
    {
        if ($price->isManual() && $this->booking->price()->boValue()->isManual()) {
            throw new \Exception('Нельзя');
        }
    }
}
