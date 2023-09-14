<?php

namespace Module\Booking\Airport\Domain\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Shared\Enum\CurrencyEnum;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?Booking;

    public function get(): Collection;

    public function create(OrderId $orderId, CurrencyEnum $currency, int $creatorId, int $serviceId, int $airportId, CarbonInterface $date, ?string $note = null): Booking;

    public function store(Booking $booking): bool;
}
