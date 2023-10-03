<?php

namespace Module\Booking\Transfer\Domain\Booking\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;
use Module\Booking\Common\Domain\ValueObject\CreatorId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Transfer\Domain\Booking\Booking;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?Booking;

    public function findOrFail(BookingId $id): Booking;

    public function get(): Collection;

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        int $serviceId,
        int $cityId,
        BookingPrice $price,
        CancelConditions $cancelConditions,
        ?string $note = null
    ): Booking;

    public function store(Booking $booking): bool;

    public function delete(Booking $booking): void;

    public function query(): Builder;

    /**
     * @param int[] $ids
     * @return void
     */
    public function bulkDelete(array $ids): void;
}
