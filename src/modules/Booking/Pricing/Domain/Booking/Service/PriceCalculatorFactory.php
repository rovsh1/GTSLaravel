<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\Support\ContainerInterface;

class PriceCalculatorFactory
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    public function build(ServiceTypeEnum $serviceType): PriceCalculatorInterface
    {
        if (in_array($serviceType, ServiceTypeEnum::getAirportCases())) {
            return $this->container->make(AirportServicePriceCalculator::class);
        } elseif (in_array($serviceType, ServiceTypeEnum::getTransferCases())) {
            return $this->container->make(TransferServicePriceCalculator::class);
        } elseif ($serviceType === ServiceTypeEnum::HOTEL_BOOKING) {
            return $this->container->make(HotelPriceCalculator::class);
        } elseif ($serviceType === ServiceTypeEnum::OTHER_SERVICE) {
            return $this->container->make(OtherServicePriceCalculator::class);
        } else {
            throw new \Exception("Booking service [$serviceType->name] Not implemented");
        }
    }
}
