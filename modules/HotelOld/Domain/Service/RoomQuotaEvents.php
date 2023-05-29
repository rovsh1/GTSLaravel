<?php

namespace Module\HotelOld\Domain\Service;

use Module\HotelOld\Domain\Event\QuotaAdded;
use Module\HotelOld\Domain\Event\QuotaRemoved;
use Module\HotelOld\Domain\Event\QuotaReserved;
use Module\HotelOld\Domain\Event\QuotaSold;
use Module\HotelOld\Domain\Repository\QuotaRepositoryInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Support\Facades\DateTimeInterface;

class RoomQuotaEvents
{
    public function __construct(
        private readonly QuotaRepositoryInterface $quotaRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function addQuota(int $roomId, DateTimeInterface $date, int $count): void
    {
        $this->registerQuotaEvent(QuotaAdded::class, $roomId, $date, $count);
    }

    public function removeQuota(int $roomId, DateTimeInterface $date, int $count): void
    {
        $this->registerQuotaEvent(QuotaRemoved::class, $roomId, $date, $count);
    }

    public function reserveQuota(int $roomId, DateTimeInterface $date, int $count): void
    {
        $this->registerQuotaEvent(QuotaReserved::class, $roomId, $date, $count);
    }

    public function sellQuota(int $roomId, DateTimeInterface $date, int $count): void
    {
        $this->registerQuotaEvent(QuotaSold::class, $roomId, $date, $count);
    }

    private function registerQuotaEvent(string $eventClass, int $roomId, DateTimeInterface $date, int $count): void
    {
        $quota = $this->quotaRepository->findByDateOrCreate($roomId, $date);

        $event = new $eventClass($quota->id(), $roomId, $date, $count);

        $this->quotaRepository->registerEvent($event);

        $this->eventDispatcher->dispatch($event);
    }
}
