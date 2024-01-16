<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use RuntimeException;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

class DetailsBuilderFactory
{
    public function __construct(
        private readonly ModuleInterface $container
    ) {}

    public function build(ServiceTypeEnum $serviceType): mixed
    {
        return match ($serviceType) {
            ServiceTypeEnum::HOTEL_BOOKING => $this->container->make(HotelBookingBuilder::class),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->container->make(TransferToAirportBuilder::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->container->make(TransferFromAirportBuilder::class),
            ServiceTypeEnum::CIP_MEETING_IN_AIRPORT => $this->container->make(CIPMeetingInAirportBuilder::class),
            ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT => $this->container->make(CIPSendoffInAirportBuilder::class),
            ServiceTypeEnum::INTERCITY_TRANSFER => $this->container->make(IntercityTransferBuilder::class),
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->container->make(CarRentWithDriverBuilder::class),
            ServiceTypeEnum::DAY_CAR_TRIP => $this->container->make(DayCarTripBuilder::class),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->container->make(TransferToRailwayBuilder::class),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->container->make(TransferFromRailwayBuilder::class),
            ServiceTypeEnum::OTHER_SERVICE => $this->container->make(OtherServiceBuilder::class),
            default => throw new RuntimeException('Service type repository not implemented'),
        };
    }
}
