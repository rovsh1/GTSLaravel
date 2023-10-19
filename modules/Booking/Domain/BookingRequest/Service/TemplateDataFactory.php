<?php

namespace Module\Booking\Domain\BookingRequest\Service;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\CommonData;
use Module\Booking\Domain\BookingRequest\Service\TemplateData\HotelBookingDataFactory;
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
        return $this->module->make(CommonData::class, ['bookingId' => $booking->id()]);
    }

    private function getServiceFactoryClass(ServiceTypeEnum $serviceType): string
    {
        if ($serviceType === ServiceTypeEnum::HOTEL_BOOKING) {
            return HotelBookingDataFactory::class;
        } elseif ($serviceType->isTransferService()) {
            throw new \Exception('Not implemented');
        } else {
            throw new \Exception('Not implemented');
        }
    }
}
