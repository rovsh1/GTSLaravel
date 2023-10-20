<?php

namespace Module\Booking\Domain\BookingRequest\Service\Factory;

use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\BookingRequest\Service\Dto\ServiceDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking\CarDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\TransferBooking;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Shared\Contracts\Service\TranslatorInterface;

class TransferBookingDataFactory
{
    public function __construct(
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly TranslatorInterface $translator,
    ) {}

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        $repository = $this->detailsRepositoryFactory->build($booking);
        $bookingDetails = $repository->findOrFail($booking->id());

        $serviceDto = new ServiceDto(
            $bookingDetails->serviceInfo()->title(),
            $this->translator->translateEnum($bookingDetails->serviceType()),
            $bookingDetails->serviceType()
        );

        $cars = [];
        /** @var CarBid $carBid */
        foreach ($bookingDetails->carBids() as $carBid) {
            $car = $this->supplierAdapter->findCar($carBid->carId()->value());
            $cars[] = new CarDto(
                $car->mark,
                $car->model,
                $carBid->carsCount(),
                $carBid->passengersCount(),
                $carBid->baggageCount(),
                $carBid->babyCount(),
            );
        }

        return match ($requestType) {
            RequestTypeEnum::BOOKING,
            RequestTypeEnum::CHANGE,
            RequestTypeEnum::CANCEL => new TransferBooking\BookingRequest(
                $serviceDto,
                $cars,
                $bookingDetails->meetingTablet(),
                '{место подачи авто}'
            ),
        };
    }
}
