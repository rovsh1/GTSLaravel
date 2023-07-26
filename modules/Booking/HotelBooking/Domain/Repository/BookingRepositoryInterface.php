<?php

namespace Module\Booking\HotelBooking\Domain\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface as Base;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\HotelInfo;
use Module\Shared\Domain\ValueObject\Id;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?Booking;

    public function get(): Collection;

    public function query(): Builder;

    public function create(
        Id $orderId,
        Id $creatorId,
        BookingPeriod $period,
        ?string $note = null,
        HotelInfo $hotelInfo,
        CancelConditions $cancelConditions
    ): Booking;

    public function store(Booking $booking): bool;

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return BookingInterface[]
     */
    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array;

    /**
     * @param int|null $hotelId
     * @return BookingInterface[]
     */
    public function searchActive(?int $hotelId): array;

    /**
     * @param int[] $ids
     * @return void
     */
    public function bulkDelete(array $ids): void;
}
