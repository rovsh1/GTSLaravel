<?php

namespace Pkg\Booking\Requesting\Service\TemplateRenderer;

use Module\Booking\Shared\Domain\Booking\Booking;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Factory\AirportBookingDataFactory;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Factory\CommonDataFactory;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Factory\HotelBookingDataFactory;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Factory\TransferBookingDataFactory;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\TemplateDataInterface;
use Sdk\Booking\Enum\RequestTypeEnum;
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
