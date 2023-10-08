<?php

namespace Module\Booking\Deprecated\TransferBooking\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Deprecated\TransferBooking\TransferBooking;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;

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

    public function delete(BookingInterface|TransferBooking $booking): void;
}
