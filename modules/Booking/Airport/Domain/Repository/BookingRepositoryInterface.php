<?php

namespace Module\Booking\Airport\Domain\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Airport\Domain\Entity\Airport;
use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Airport\Domain\Entity\Service;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface as Base;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?Booking;

    public function get(): Collection;

    public function create(int $orderId, int $creatorId, Service $service, Airport $airport, CarbonInterface $date, ?string $note = null): Booking;

    public function store(Booking $booking): bool;
}
