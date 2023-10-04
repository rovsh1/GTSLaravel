<?php

namespace Module\Booking\Domain\TransferBooking\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Booking\Domain\TransferBooking\TransferBooking;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?TransferBooking;

    public function findOrFail(BookingId $id): TransferBooking;

    public function get(): Collection;

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        int $serviceId,
        int $cityId,
        BookingPrice $price,
        CancelConditions $cancelConditions,
        ?string $note = null
    ): TransferBooking;

    public function store(TransferBooking $booking): bool;

    public function delete(TransferBooking $booking): void;

    public function query(): Builder;

    /**
     * @param int[] $ids
     * @return void
     */
    public function bulkDelete(array $ids): void;
}
