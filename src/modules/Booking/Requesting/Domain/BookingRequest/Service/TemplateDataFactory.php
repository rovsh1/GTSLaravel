<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Service;

use Module\Booking\Requesting\Domain\BookingRequest\Service\Factory\AirportBookingDataFactory;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Factory\CommonDataFactory;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Factory\HotelBookingDataFactory;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Factory\TransferBookingDataFactory;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

class TemplateDataFactory
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        $serviceFactory = $this->getServiceFactoryClass($booking->serviceType());

        return $this->container->make($serviceFactory)->build($booking, $requestType);
    }

    public function buildCommon(Booking $booking): TemplateDataInterface
    {
        return $this->container->make(CommonDataFactory::class)->build($booking);
    }

    private function getServiceFactoryClass(ServiceTypeEnum $serviceType): string
    {
        if ($serviceType === ServiceTypeEnum::HOTEL_BOOKING) {
            return HotelBookingDataFactory::class;
        } elseif ($serviceType->isTransferService()) {
            return TransferBookingDataFactory::class;
        } elseif ($serviceType->isAirportService()) {
            return AirportBookingDataFactory::class;
        } else {
            throw new \Exception('Not implemented');
        }
    }
}
