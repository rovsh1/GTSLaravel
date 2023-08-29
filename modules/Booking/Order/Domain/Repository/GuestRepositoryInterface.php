<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Repository;

use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Domain\Entity\Guest;
use Module\Booking\Order\Domain\ValueObject\GuestId;
use Module\Booking\Order\Domain\ValueObject\GuestIdsCollection;
use Module\Shared\Domain\ValueObject\GenderEnum;

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
