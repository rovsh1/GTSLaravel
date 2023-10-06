<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Repository;

use Module\Booking\Domain\Order\Entity\Guest;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
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
     * @param GuestIdCollection $ids
     * @return array<int, Guest>
     */
    public function get(GuestIdCollection $ids): array;

    public function store(Guest $guest): bool;

    public function delete(GuestId $id): void;
}
