<?php

namespace GTS\Hotel\Domain\Service;

use Custom\Framework\Contracts\Event\DomainEventDispatcherInterface;
use Custom\Framework\Support\DateTimeInterface;
use GTS\Hotel\Domain\Event\BookingClosed;
use GTS\Hotel\Domain\Event\BookingOpened;
use GTS\Hotel\Domain\Event\QuotaAdded;
use GTS\Hotel\Domain\Event\QuotaReserved;
use GTS\Hotel\Domain\Event\QuotaReset;
use GTS\Hotel\Domain\Event\QuotaSold;
use GTS\Hotel\Domain\Repository\QuotaEventRepositoryInterface;
use GTS\Hotel\Domain\Repository\QuotaRepositoryInterface;

class Booking
{
    public function __construct(
        private readonly QuotaEventRepositoryInterface $eventRepository,
        private readonly QuotaRepositoryInterface $quotaRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function addQuota(int $roomId, DateTimeInterface $date, int $count): void
    {
        $this->registerQuotaEvent(QuotaAdded::class, $roomId, $date, $count);
    }

    public function reserveQuota(int $roomId, DateTimeInterface $date, int $count): void
    {
        $this->registerQuotaEvent(QuotaReserved::class, $roomId, $date, $count);
    }

    public function sellQuota(int $roomId, DateTimeInterface $date, int $count): void
    {
        $this->registerQuotaEvent(QuotaSold::class, $roomId, $date, $count);
    }

    public function resetQuota(int $roomId, DateTimeInterface $date): void
    {
        $this->eventRepository->reset($roomId, $date);

        $this->eventDispatcher->dispatch(new QuotaReset($roomId, $date));
    }

    public function openBooking(int $roomId, DateTimeInterface $date): void
    {
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
        return $this->eventRepository->getAvailableCount($roomId, $date);
    }

    private function registerQuotaEvent(string $eventClass, int $roomId, DateTimeInterface $date, int $count): void
    {
        $event = new $eventClass($roomId, $date, $count);

        $this->eventRepository->register($event);

        $this->eventDispatcher->dispatch($event);
    }
}
