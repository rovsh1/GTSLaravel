<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Service;

use Illuminate\Support\Facades\DB;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Module\Booking\Shared\Infrastructure\Service\UnitOfWork\BookingCommiter;
use Module\Booking\Shared\Infrastructure\Service\UnitOfWork\IdentityMap;
use Sdk\Booking\Contracts\Entity\BookingPartInterface;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class BookingUnitOfWork implements BookingUnitOfWorkInterface
{
    private bool $commitState = false;

    private array $commitCallbacks = [];

    public function __construct(
        private readonly IdentityMap $identityMap,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingCommiter $bookingCommiter,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly DomainEventDispatcherInterface $domainEventDispatcher,
    ) {}

    public function findOrFail(BookingId $bookingId): Booking
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $this->persist($booking);

        return $booking;
    }

    public function getDetails(BookingId $bookingId): DetailsInterface
    {
        $details = $this->detailsRepository->findOrFail($bookingId);
        $this->persist($details);

        return $details;
    }

    public function persist(Booking|BookingPartInterface $entity): void
    {
        $this->identityMap->add($entity);
    }

    public function touch(BookingId $bookingId): void
    {
        $this->bookingCommiter->touch($bookingId);
    }

    public function commiting(\Closure $callback): void
    {
        $this->commitCallbacks[] = $callback;
    }

    public function commit(): void
    {
        if ($this->commitState) {
            return;
        }

        $this->commitState = true;

        DB::beginTransaction();

        while ($entity = $this->identityMap->shift()) {
            if ($entity instanceof Booking) {
                $this->bookingCommiter->store($entity);
                continue;
            }

            $this->bookingCommiter->touch($entity->bookingId());

            if ($entity instanceof DetailsInterface) {
                $this->detailsRepository->store($entity);
            } elseif ($entity instanceof HotelAccommodation) {
                $this->accommodationRepository->store($entity);
            }

            $this->domainEventDispatcher->dispatch(...$entity->pullEvents());
        }

        foreach ($this->commitCallbacks as $callback) {
            $callback();
        }

        $this->bookingCommiter->finish();

        DB::commit();

        //@todo catch changes, add events
        $this->identityMap->reset();
        $this->commitState = false;
    }
}
