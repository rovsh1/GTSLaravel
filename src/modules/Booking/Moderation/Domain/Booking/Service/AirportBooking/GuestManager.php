<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Service\AirportBooking;

use Carbon\Carbon;
use Module\Booking\Moderation\Domain\Booking\Event\GuestBinded;
use Module\Booking\Moderation\Domain\Booking\Event\GuestUnbinded;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Domain\Booking\Exception\NotFoundAirportServicePrice;
use Module\Booking\Shared\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Shared\Domain\Booking\Repository\AirportBookingGuestRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GuestManager
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly AirportBookingGuestRepositoryInterface $bookingGuestRepository,
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
        $detailsRepository = $this->detailsRepositoryFactory->build($booking);
        $details = $detailsRepository->find($bookingId);
        $this->validateSupplier($booking, $details);

        $this->bookingGuestRepository->bind($bookingId, $guestId);
        $details->addGuest($guestId);
        $detailsRepository->store($details);
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
        $detailsRepository = $this->detailsRepositoryFactory->build($booking);
        $details = $detailsRepository->find($bookingId);
        $this->bookingGuestRepository->unbind($bookingId, $guestId);
        $details->removeGuest($guestId);
        $detailsRepository->store($details);
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
     * @param CIPMeetingInAirport $details
     * @return void
     * @throws EntityNotFoundException
     * @throws NotFoundAirportServicePrice
     */
    public function validateSupplier(Booking $booking, CIPMeetingInAirport|CIPSendoffInAirport $details): void
    {
        $supplier = $this->supplierAdapter->find($details->serviceInfo()->supplierId());
        if ($supplier === null) {
            throw new EntityNotFoundException('Supplier not found');
        }
//        $contract = $this->supplierAdapter->findAirportServiceContract($booking->serviceInfo()->id());
//        if ($contract === null) {
//            throw new EntityNotFoundException('Supplier\'s service contract not found');
//        }
        $date = $details instanceof CIPMeetingInAirport ? $details->arrivalDate() : $details->departureDate();

        $price = $this->supplierAdapter->getAirportServicePrice(
            $supplier->id,
            $details->serviceInfo()->id(),
            $booking->prices()->clientPrice()->currency(),
            new Carbon($date)
        );
        if ($price === null) {
            throw new NotFoundAirportServicePrice();
        }
    }
}
