<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Moderation\Application\Exception\NotFoundServiceCancelConditionsException;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException as NotFoundApplicationException;
use Module\Booking\Moderation\Application\Exception\ServiceDateUndefinedException;
use Module\Booking\Moderation\Domain\Booking\Exception\NotFoundServiceCancelConditions;
use Module\Booking\Moderation\Domain\Booking\Service\CancelConditionsUpdater;
use Module\Booking\Moderation\Domain\Booking\Service\TransferBooking\CarBidPriceBuilder;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CarBidUpdateHelper
{
    private Booking $booking;

    private TransferDetailsInterface $details;

    private int $carId;

    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly CarBidPriceBuilder $carBidPriceBuilder,
        private readonly CancelConditionsUpdater $cancelConditionsUpdater,
    ) {}

    public function boot(int $bookingId, int $carId = null): void
    {
        if ($carId) {
            $car = $this->supplierAdapter->findCar($carId);
            if ($car === null) {
                throw new EntityNotFoundException('Car not found');
            }
            $this->carId = $carId;
        }

        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
        $details = $this->detailsRepository->findOrFail($booking->id());
        assert($details instanceof TransferDetailsInterface);

        if ($details->serviceDate() === null) {
            throw new ServiceDateUndefinedException();
        }

        $this->booking = $booking;
        $this->details = $details;
    }

    public function booking(): Booking
    {
        return $this->booking;
    }

    public function details(): TransferDetailsInterface
    {
        return $this->details;
    }

    public function prices(): CarBidPrices
    {
        $details = $this->details;

        try {
            return $this->carBidPriceBuilder->build(
                $details->serviceInfo()->supplierId(),
                $details->serviceInfo()->id(),
                $this->carId,
                $this->booking->prices()->clientPrice()->currency(),
                $details->serviceDate(),
            );
        } catch (NotFoundTransferServicePrice $e) {
            throw new NotFoundApplicationException($e);
        }
    }

    public function store(): void
    {
        $this->detailsRepository->store($this->details);

        try {
            $this->cancelConditionsUpdater->update($this->booking);
        } catch (NotFoundServiceCancelConditions $e) {
            throw new NotFoundServiceCancelConditionsException($e);
        }
    }
}