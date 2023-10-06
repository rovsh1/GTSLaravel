<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service;

use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\PriceItem;

interface BookingCalculatorInterface
{
    public function calculateGrossPrice(BookingInterface $booking): PriceItem;

    public function calculateNetPrice(BookingInterface $booking): PriceItem;
}
