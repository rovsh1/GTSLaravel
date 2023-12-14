<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Support\RepositoryInstances;

class BookingRepository implements BookingRepositoryInterface
{
    private static RepositoryInstances $instances;

    public function __construct(
        private readonly BookingDbContextInterface $bookingDbContext
    ) {
        self::$instances = new RepositoryInstances();
    }

    public function add(Booking $booking): void
    {
        self::$instances->add($booking->id(), $booking);
    }

    public function get(): array
    {
        return self::$instances->all();
    }

    public function find(BookingId $id): ?Booking
    {
        if (self::$instances->has($id)) {
            return self::$instances->get($id);
        }

        $booking = $this->bookingDbContext->find($id);
        if ($booking === null) {
            return null;
        }

        self::$instances->add($booking->id(), $booking);

        return $booking;
    }

    public function getByOrderId(OrderId $orderId): array
    {
        //@todo use instances
        return $this->bookingDbContext->getByOrderId($orderId);
    }

    /**
     * @param GuestId $guestId
     * @return Booking[]
     */
    public function getByGuestId(GuestId $guestId): array
    {
        //@todo use instances
        return $this->bookingDbContext->getByGuestId($guestId);
    }

    public function findOrFail(BookingId $id): Booking
    {
        return $this->find($id) ?? throw new EntityNotFoundException("Booking[$id] not found");
    }
}
