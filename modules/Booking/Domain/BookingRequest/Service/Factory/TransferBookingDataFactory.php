<?php

namespace Module\Booking\Domain\BookingRequest\Service\Factory;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\TransferBooking;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;

class TransferBookingDataFactory
{
    public function __construct(
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
    ) {}

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        $repository = $this->detailsRepositoryFactory->build($booking);
        $bookingDetails = $repository->findOrFail($booking->id());

        return match ($requestType) {
            RequestTypeEnum::BOOKING => new TransferBooking\BookingRequest($detailsData),
            RequestTypeEnum::CHANGE => new TransferBooking\BookingRequest($detailsData),
            RequestTypeEnum::CANCEL => new TransferBooking\BookingRequest($detailsData),
        };
    }
}
