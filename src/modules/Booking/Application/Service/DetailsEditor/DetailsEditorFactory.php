<?php

declare(strict_types=1);

namespace Module\Booking\Application\Service\DetailsEditor;

use Module\Booking\Domain\Booking\Booking;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\Support\ContainerInterface;

class DetailsEditorFactory
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    public function build(Booking $booking): EditorInterface
    {
        return match ($booking->serviceType()) {
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\CarRentWithDriver::class),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\TransferToAirport::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\TransferFromAirport::class),
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\CIPRoomInAirport::class),
            ServiceTypeEnum::HOTEL_BOOKING => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\HotelBooking::class),
            ServiceTypeEnum::INTERCITY_TRANSFER => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\IntercityTransfer::class),
            ServiceTypeEnum::DAY_CAR_TRIP => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\DayCarTrip::class),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\TransferFromRailway::class),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\TransferToRailway::class),
            ServiceTypeEnum::OTHER_SERVICE => $this->container->make(
                \Module\Booking\Application\Service\DetailsEditor\Editor\OtherService::class),
            default => throw new \Exception('Unknown service details')
        };
    }
}
