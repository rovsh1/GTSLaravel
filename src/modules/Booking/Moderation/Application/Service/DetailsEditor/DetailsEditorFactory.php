<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor;

use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\CarRentWithDriver;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\CIPMeetingInAirport;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\CIPSendoffInAirport;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\DayCarTrip;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\EditorInterface;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\HotelBooking;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\IntercityTransfer;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\OtherService;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\TransferFromAirport;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\TransferFromRailway;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\TransferToAirport;
use Module\Booking\Moderation\Application\Service\DetailsEditor\Editor\TransferToRailway;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

class DetailsEditorFactory
{
    public function __construct(
        private readonly ModuleInterface $container,
    ) {}

    public function build(ServiceTypeEnum $serviceType): EditorInterface
    {
        return match ($serviceType) {
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->container->make(CarRentWithDriver::class),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->container->make(TransferToAirport::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->container->make(TransferFromAirport::class),
            ServiceTypeEnum::CIP_MEETING_IN_AIRPORT => $this->container->make(CIPMeetingInAirport::class),
            ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT => $this->container->make(CIPSendoffInAirport::class),
            ServiceTypeEnum::HOTEL_BOOKING => $this->container->make(HotelBooking::class),
            ServiceTypeEnum::INTERCITY_TRANSFER => $this->container->make(IntercityTransfer::class),
            ServiceTypeEnum::DAY_CAR_TRIP => $this->container->make(DayCarTrip::class),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->container->make(TransferFromRailway::class),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->container->make(TransferToRailway::class),
            ServiceTypeEnum::OTHER_SERVICE => $this->container->make(OtherService::class),
            default => throw new \Exception('Unknown service details')
        };
    }
}
