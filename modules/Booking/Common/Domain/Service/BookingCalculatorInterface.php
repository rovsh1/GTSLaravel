<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\ValueObject\PriceItem;

interface BookingCalculatorInterface
{
    public function calculateGrossPrice(BookingInterface $booking): PriceItem;

    public function calculateNetPrice(BookingInterface $booking): PriceItem;
}
