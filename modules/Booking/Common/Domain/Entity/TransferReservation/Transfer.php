<?php

namespace Module\Booking\Common\Domain\Entity\TransferReservation;

use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Entity\ReservationItemInterface;
use Module\Booking\PriceCalculator\Domain\Service\ReservationCalculationInterface;
use Module\Booking\PriceCalculator\Domain\Service\TransferReservationCalculation;

class Transfer implements ReservationItemInterface, BookingRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new TransferReservationCalculation();
    }
}
