<?php

namespace Module\Booking\Shared\Infrastructure\Service\UnitOfWork;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class BookingCommiter
{
    private array $bookingUpdatedFlags = [];

    public function __construct(
        private readonly BookingDbContextInterface $bookingDbContext,
        private readonly DomainEventDispatcherInterface $domainEventDispatcher,
    ) {}

    public function touch(BookingId $bookingId): void
    {
        if (!isset($this->bookingUpdatedFlags[$bookingId->value()])) {
            $this->bookingUpdatedFlags[$bookingId->value()] = false;
        }
    }

    public function store(Booking $booking): void
    {
        $this->bookingUpdatedFlags[$booking->id()->value()] = true;
        $this->bookingDbContext->store($booking);
        $this->domainEventDispatcher->dispatch(...$booking->pullEvents());
    }

    public function finish(): void
    {
        foreach ($this->bookingUpdatedFlags as $id => $flag) {
            if (false === $flag) {
                $this->bookingDbContext->touch(new BookingId($id));
            }
        }

        $this->bookingUpdatedFlags = [];
    }
}