<?php

namespace Module\Booking\Common\Domain\Entity\TransferReservation;

use Module\Booking\Common\Domain\Entity\ReservationItemInterface;
use Module\Booking\Common\Domain\Entity\ReservationRequestableInterface;
use Module\Booking\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use Module\Booking\Common\Domain\Service\PriceCalculator\TransferReservationCalculation;

class TransferReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new TransferReservationCalculation();
    }
}
