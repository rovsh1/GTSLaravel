<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Infrastructure\Service;

use Module\Booking\Requesting\Domain\Service\ChangesRegistratorInterface;
use Sdk\Booking\ValueObject\BookingId;

class ChangesRegistrator implements ChangesRegistratorInterface
{
    public function register(
        BookingId $bookingId,
        array|null $payload,
        array $context = []
    ): void {}
}
