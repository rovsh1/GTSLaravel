<?php

namespace Module\Booking\Common\Domain\Entity\TransferReservation;

use Module\Booking\Common\Domain\Entity\ReservationItemInterface;
use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use Module\Booking\Common\Domain\Service\PriceCalculator\TransferReservationCalculation;

class Transfer implements ReservationItemInterface, BookingRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new TransferReservationCalculation();
    }
}
