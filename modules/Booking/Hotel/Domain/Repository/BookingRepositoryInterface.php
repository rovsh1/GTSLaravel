<?php

namespace Module\Booking\Hotel\Domain\Repository;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Hotel\Domain\Entity\Booking;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?Booking;

    public function get(): Collection;

    public function create(
        int $orderId,
        int $creatorId,
        int $hotelId,
        CarbonPeriod $period,
        ?string $note = null,
        mixed $hotelDto,
        mixed $hotelMarkupSettings
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
}
