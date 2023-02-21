<?php

namespace Module\Reservation\Common\Domain\Service\PriceCalculator;

use Module\Reservation\Common\Domain\Entity\ReservationInterface;

interface ReservationPriceInterface
{
    public function calculate(ReservationInterface $reservation);
}
