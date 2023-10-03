<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Repository;

use Module\Booking\Domain\Order\Entity\Guest;
use Module\Booking\Domain\Order\ValueObject\GuestId;
use Module\Booking\Domain\Order\ValueObject\GuestIdsCollection;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Shared\Enum\GenderEnum;

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
     * @param GuestIdsCollection $ids
     * @return array<int, Guest>
     */
    public function get(GuestIdsCollection $ids): array;

    public function store(Guest $guest): bool;

    public function delete(GuestId $id): void;
}
