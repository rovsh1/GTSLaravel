<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Guest\Repository;

use Module\Booking\Shared\Domain\Guest\Guest;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Enum\GenderEnum;

interface GuestRepositoryInterface
{
    public function create(
        OrderId $orderId,
        string $fullName,
        int $countryId,
        GenderEnum $gender,
        bool $isAdult,
        ?int $age
    ): Guest;

    public function find(GuestId $id): ?Guest;

    /**
     * @param GuestIdCollection $ids
     * @return array<int, Guest>
     */
    public function get(GuestIdCollection $ids): array;

    public function store(Guest $guest): bool;

    public function delete(GuestId $id): void;
}
