<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\ValueObject\BookingId;

interface DetailsRepositoryInterface
{
    /**
     * @return DetailsInterface[]
     */
    public function get(): array;

    public function find(BookingId $id): ?DetailsInterface;

    public function findOrFail(BookingId $id): DetailsInterface;

    public function store(DetailsInterface $details): void;
}
