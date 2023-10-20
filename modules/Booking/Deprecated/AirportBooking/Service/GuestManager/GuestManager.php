<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\AirportBooking\Service\GuestManager;

use Carbon\Carbon;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\Booking\Event\GuestBinded;
use Module\Booking\Domain\Booking\Event\GuestUnbinded;
use Module\Booking\Domain\Booking\Exception\NotFoundAirportServicePrice;
use Module\Booking\Domain\Booking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GuestManager
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly CIPRoomInAirportRepositoryInterface $detailsRepository,
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @param BookingId $bookingId
     * @param GuestId $guestId
     * @return void
     * @throws EntityNotFoundException
     * @throws NotFoundAirportServicePrice
     */
    public function bind(BookingId $bookingId, GuestId $guestId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $details = $this->detailsRepository->find($bookingId);
        $this->validateSupplier($booking, $details);

        $this->bookingGuestRepository->bind($bookingId, $guestId);
        $details->addGuest($guestId);
        $this->detailsRepository->store($details);
        $this->eventDispatcher->dispatch(
            new GuestBinded(
                $booking->id(),
                $booking->orderId(),
                $guestId,
            )
        );
    }

    public function unbind(BookingId $bookingId, GuestId $guestId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $details = $this->detailsRepository->find($bookingId);
        $this->bookingGuestRepository->unbind($bookingId, $guestId);
        $details->removeGuest($guestId);
        $this->detailsRepository->store($details);
        $this->eventDispatcher->dispatch(
            new GuestUnbinded(
                $booking->id(),
                $booking->orderId(),
                $guestId,
            )
        );
    }

    /**
     * @param Booking $booking
     * @param CIPRoomInAirport $details
     * @return void
     * @throws EntityNotFoundException
     * @throws NotFoundAirportServicePrice
     */
    public function validateSupplier(Booking $booking, CIPRoomInAirport $details): void
    {
        $supplier = $this->supplierAdapter->find($details->serviceInfo()->supplierId());
        if ($supplier === null) {
            throw new EntityNotFoundException('Supplier not found');
        }
//        $contract = $this->supplierAdapter->findAirportServiceContract($booking->serviceInfo()->id());
//        if ($contract === null) {
//            throw new EntityNotFoundException('Supplier\'s service contract not found');
//        }
        $price = $this->supplierAdapter->getAirportServicePrice(
            $supplier->id,
            $details->serviceInfo()->id(),
            $booking->prices()->clientPrice()->currency(),
            new Carbon($details->serviceDate())
        );
        if ($price === null) {
            throw new NotFoundAirportServicePrice();
        }
    }
}
