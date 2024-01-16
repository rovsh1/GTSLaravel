<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

class DetailsStorageFactory
{
    public function __construct(
        private readonly ModuleInterface $container
    ) {}

    public function build(ServiceTypeEnum $serviceType): mixed
    {
        return match ($serviceType) {
            ServiceTypeEnum::HOTEL_BOOKING => $this->container->make(HotelBookingStorage::class),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->container->make(TransferToAirportStorage::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->container->make(TransferFromAirportStorage::class),
            ServiceTypeEnum::CIP_MEETING_IN_AIRPORT => $this->container->make(CIPMeetingInAirportStorage::class),
            ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT => $this->container->make(CIPSendoffInAirportStorage::class),
            ServiceTypeEnum::INTERCITY_TRANSFER => $this->container->make(IntercityTransferStorage::class),
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->container->make(CarRentWithDriverStorage::class),
            ServiceTypeEnum::DAY_CAR_TRIP => $this->container->make(DayCarTripStorage::class),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->container->make(TransferToRailwayStorage::class),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->container->make(TransferFromRailwayStorage::class),
            ServiceTypeEnum::OTHER_SERVICE => $this->container->make(OtherServiceStorage::class),
            default => throw new \RuntimeException('Service type repository not implemented'),
        };
    }
}
