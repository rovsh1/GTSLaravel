<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\AirportBooking\Service\GuestManager;

use Module\Booking\Deprecated\AirportBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Deprecated\AirportBooking\AirportBooking;
use Module\Booking\Deprecated\AirportBooking\Event\GuestBinded;
use Module\Booking\Deprecated\AirportBooking\Event\GuestUnbinded;
use Module\Booking\Deprecated\AirportBooking\Exception\NotFoundAirportServicePrice;
use Module\Booking\Deprecated\AirportBooking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Deprecated\AirportBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GuestManager
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
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
        $this->validateSupplier($booking);

        $this->bookingGuestRepository->bind($bookingId, $guestId);
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
        $this->bookingGuestRepository->unbind($bookingId, $guestId);
        $this->eventDispatcher->dispatch(
            new GuestUnbinded(
                $booking->id(),
                $booking->orderId(),
                $guestId,
            )
        );
    }

    /**
     * @param AirportBooking $booking
     * @return void
     * @throws EntityNotFoundException
     * @throws NotFoundAirportServicePrice
     */
    public function validateSupplier(AirportBooking $booking): void
    {
        $supplier = $this->supplierAdapter->find($booking->serviceInfo()->supplierId());
        if ($supplier === null) {
            throw new EntityNotFoundException('Supplier not found');
        }
//        $contract = $this->supplierAdapter->findAirportServiceContract($booking->serviceInfo()->id());
//        if ($contract === null) {
//            throw new EntityNotFoundException('Supplier\'s service contract not found');
//        }
        $price = $this->supplierAdapter->getAirportServicePrice(
            $supplier->id,
            $booking->serviceInfo()->id(),
            $booking->airportInfo()->id(),
            $booking->price()->grossPrice()->currency(),
            $booking->date()
        );
        if ($price === null) {
            throw new NotFoundAirportServicePrice();
        }
    }
}
