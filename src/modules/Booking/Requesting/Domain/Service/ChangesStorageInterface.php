<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Service;

use Module\Booking\Requesting\Domain\Entity\Changes;
use Module\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\ValueObject\BookingId;

interface ChangesStorageInterface
{
    public function find(ChangesIdentifier $identifier): ?Changes;

    public function store(Changes $changes): void;

    public function clear(BookingId $bookingId): void;
}
