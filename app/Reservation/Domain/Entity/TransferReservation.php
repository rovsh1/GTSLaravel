<?php

namespace GTS\Reservation\Domain\Entity;

use GTS\Reservation\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use GTS\Reservation\Domain\Service\PriceCalculator\TransferReservationCalculation;

class TransferReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new TransferReservationCalculation();
    }
}
