<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Entity\ReservationInterface;

interface DocumentGeneratorInterface
{
    public function generate(Request $request, ReservationInterface $booking): void;
}
