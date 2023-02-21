<?php

namespace Module\Reservation\Common\Domain\Entity\TransferReservation;

use Module\Reservation\Common\Domain\Entity\ReservationItemInterface;
use Module\Reservation\Common\Domain\Entity\ReservationRequestableInterface;
use Module\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use Module\Reservation\Common\Domain\Service\PriceCalculator\TransferReservationCalculation;

class TransferReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new TransferReservationCalculation();
    }
}
