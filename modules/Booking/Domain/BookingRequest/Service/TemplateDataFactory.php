<?php

namespace Module\Booking\Domain\BookingRequest\Service;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\BookingRequest\Service\Factory\AirportBookingDataFactory;
use Module\Booking\Domain\BookingRequest\Service\Factory\CommonDataFactory;
use Module\Booking\Domain\BookingRequest\Service\Factory\HotelBookingDataFactory;
use Module\Booking\Domain\BookingRequest\Service\Factory\TransferBookingDataFactory;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\ModuleInterface;

class TemplateDataFactory
{
    public function __construct(
        private readonly ModuleInterface $module,
    ) {}

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        $serviceFactory = $this->getServiceFactoryClass($booking->serviceType());

        return $this->module->make($serviceFactory)->build($booking, $requestType);
    }

    public function buildCommon(Booking $booking): TemplateDataInterface
    {
        return $this->module->make(CommonDataFactory::class)->build($booking);
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
