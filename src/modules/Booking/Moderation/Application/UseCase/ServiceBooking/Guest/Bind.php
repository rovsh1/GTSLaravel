<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\Guest;

use Carbon\Carbon;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException;
use Module\Booking\Moderation\Application\Service\GuestValidator;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Contracts\Entity\AirportDetailsInterface;
use Sdk\Booking\Entity\Details\CIPMeetingInAirport;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Bind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly GuestValidator $guestValidator,
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    public function execute(int $bookingId, int $guestId): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));
        $details = $this->bookingUnitOfWork->getDetails($booking->id());
        assert($details instanceof AirportDetailsInterface);

        $this->guestValidator->ensureCanBindToBooking($bookingId, $guestId);

        $this->validateSupplier($booking, $details);

        $details->addGuest(new GuestId($guestId));

        $this->bookingUnitOfWork->commit();
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
