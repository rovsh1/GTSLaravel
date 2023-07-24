<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;

interface RequestGeneratorInterface
{
    public function generate(Request $request, BookingRequestableInterface $booking): void;
}
