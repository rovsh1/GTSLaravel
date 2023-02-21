<?php

namespace Module\Hotel\Domain\Service;

use Custom\Framework\Contracts\Event\DomainEventDispatcherInterface;
use Custom\Framework\Support\DateTimeInterface;
use Module\Hotel\Domain\Repository\QuotaRepositoryInterface;
use Module\Hotel\Domain\Event\BookingClosed;
use Module\Hotel\Domain\Event\BookingOpened;

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
