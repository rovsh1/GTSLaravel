<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\Guest;

use Carbon\Carbon;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException;
use Module\Booking\Moderation\Domain\Booking\Event\GuestBinded;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Contracts\Entity\AirportDetailsInterface;
use Sdk\Booking\Entity\BookingDetails\CIPMeetingInAirport;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Bind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $guestId): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
        $details = $this->detailsRepository->findOrFail($booking->id());
        assert($details instanceof AirportDetailsInterface);
        $guest = $this->guestRepository->findOrFail(new GuestId($guestId));

        $this->validateSupplier($booking, $details);

        $details->addGuest($guest->id());
        $this->detailsRepository->store($details);
        $this->eventDispatcher->dispatch(new GuestBinded($booking, $guest));
    }

    /**
     * @param Booking $booking
     * @param CIPMeetingInAirport $details
     * @return void
     * @throws EntityNotFoundException
     * @throws NotFoundServicePriceException
     */
    private function validateSupplier(Booking $booking, AirportDetailsInterface $details): void
    {
        $supplier = $this->supplierAdapter->find($details->serviceInfo()->supplierId());
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
            throw new NotFoundServicePriceException();
        }
    }
}
