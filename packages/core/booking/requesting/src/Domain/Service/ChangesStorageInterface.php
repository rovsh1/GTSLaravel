<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Service;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\ValueObject\BookingId;

interface ChangesStorageInterface
{
    public function find(ChangesIdentifier $identifier): ?Changes;

    public function exists(ChangesIdentifier $identifier): bool;

    public function remove(ChangesIdentifier $identifier): void;

    public function store(Changes $changes): void;

    /**
     * @param BookingId $bookingId
     * @param string|null $field
     * @param array|null $excludeFields
     * @return Changes[]
     */
    public function get(BookingId $bookingId, ?string $field, ?array $excludeFields = null): array;

    public function clear(BookingId $bookingId): void;
}
