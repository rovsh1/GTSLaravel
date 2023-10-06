<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service\DocumentGenerator;

use Module\Booking\Domain\Shared\Event\Contracts\BookingRequestableInterface;

interface RequestGeneratorInterface
{
    public function generate(BookingRequestableInterface $booking): string;
}
