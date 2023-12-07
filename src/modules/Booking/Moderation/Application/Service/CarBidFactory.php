<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException as NotFoundApplicationException;
use Module\Booking\Moderation\Application\Exception\ServiceDateUndefinedException;
use Module\Booking\Moderation\Domain\Booking\Service\TransferBooking\CarBidPriceBuilder;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CarBidFactory
{
    private CarBidDataDto $data;

    private Booking $booking;

    private TransferDetailsInterface $details;

    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly CarBidPriceBuilder $carBidPriceBuilder,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    public function fromRequest(CarBidDataDto $request): void
    {
        $this->data = $request;
    }

    public function create(BookingId $bookingId): CarBid
    {
        $this->booking = $this->bookingUnitOfWork->findOrFail($bookingId);
        $details = $this->bookingUnitOfWork->getDetails($this->booking->id());
        assert($details instanceof TransferDetailsInterface);
        $this->details = $details;

        $this->validate();

        $prices = $this->prices();
        $data = $this->data;

        return $this->carBidDbContext->create(
            bookingId: $this->booking->id(),
            carId: new CarId($data->carId),
            carsCount: $data->carsCount,
            babyCount: $data->babyCount,
            passengersCount: $data->passengersCount,
            baggageCount: $data->baggageCount,
            prices: $prices,
        );
    }

    private function validate(): void
    {
        $this->ensureCarExists();
        $this->ensureBookingServiceDateDefined();
    }

    private function ensureCarExists(): void
    {
        $carId = $this->data->carId;
        $car = $this->supplierAdapter->findCar($carId);
        if ($car === null) {
            throw new EntityNotFoundException('Car not found');
        }
    }

    private function ensureBookingServiceDateDefined(): void
    {
        if ($this->details->serviceDate() === null) {
            throw new ServiceDateUndefinedException();
        }
    }

    private function prices(): CarBidPrices
    {
        $details = $this->details;

        try {
            return $this->carBidPriceBuilder->build(
                $details->serviceInfo()->supplierId(),
                $details->serviceInfo()->id(),
                $this->data->carId,
                $this->booking->prices()->clientPrice()->currency(),
                $details->serviceDate(),
            );
        } catch (NotFoundTransferServicePrice $e) {
            throw new NotFoundApplicationException($e);
        }
    }
}