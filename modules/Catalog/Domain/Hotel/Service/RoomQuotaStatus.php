<?php

namespace Module\Catalog\Domain\Hotel\Service;

use Module\Catalog\Domain\Hotel\Event\BookingClosed;
use Module\Catalog\Domain\Hotel\Event\BookingOpened;
use Module\Hotel\Domain\Repository\QuotaRepositoryInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Support\Facades\DateTimeInterface;

class RoomQuotaStatus
{
    public function __construct(
        private readonly QuotaRepositoryInterface $quotaRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function openBooking(int $roomId, DateTimeInterface $date): void
    {
        $quota = $this->quotaRepository->findByDateOrCreate($roomId, $date);

        $this->quotaRepository->open($roomId, $date);

        $this->eventDispatcher->dispatch(new BookingOpened($roomId, $date));
    }

    public function closeBooking(int $roomId, DateTimeInterface $date): void
    {
        $this->quotaRepository->close($roomId, $date);

        $this->eventDispatcher->dispatch(new BookingClosed($roomId, $date));
    }

    public function getAvailableQuota(int $roomId, DateTimeInterface $date): int
    {
        return $this->quotaRepository->getAvailableCount($roomId, $date);
    }
}
