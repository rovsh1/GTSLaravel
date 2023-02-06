<?php

namespace GTS\Reservation\Common\Domain\Entity\TransferReservation;

use GTS\Reservation\Common\Domain\Entity\ReservationItemInterface;
use GTS\Reservation\Common\Domain\Entity\ReservationRequestableInterface;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\TransferReservationCalculation;

class TransferReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new TransferReservationCalculation();
    }
}
