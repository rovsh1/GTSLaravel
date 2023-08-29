<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Repository;

use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Domain\Entity\Tourist;
use Module\Booking\Order\Domain\ValueObject\TouristId;
use Module\Booking\Order\Domain\ValueObject\TouristIdsCollection;
use Module\Shared\Domain\ValueObject\GenderEnum;

interface TouristRepositoryInterface
{
    public function create(
        OrderId $orderId,
        string $fullName,
        int $countryId,
        GenderEnum $gender,
        bool $isAdult,
        ?int $age
    ): Tourist;

    public function find(TouristId $id): ?Tourist;

    /**
     * @param TouristIdsCollection $ids
     * @return array<int, Tourist>
     */
    public function get(TouristIdsCollection $ids): array;

    public function store(Tourist $tourist): bool;

    public function delete(TouristId $id): void;
}
