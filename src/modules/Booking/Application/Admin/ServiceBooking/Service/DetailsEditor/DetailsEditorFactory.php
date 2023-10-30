<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor;

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
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->container->make(Editor\CarRentWithDriver::class),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->container->make(Editor\TransferToAirport::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->container->make(Editor\TransferFromAirport::class),
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->container->make(Editor\CIPRoomInAirport::class),
            ServiceTypeEnum::HOTEL_BOOKING => $this->container->make(Editor\HotelBooking::class),
            ServiceTypeEnum::INTERCITY_TRANSFER => $this->container->make(Editor\IntercityTransfer::class),
            ServiceTypeEnum::DAY_CAR_TRIP => $this->container->make(Editor\DayCarTrip::class),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->container->make(Editor\TransferFromRailway::class),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->container->make(Editor\TransferToRailway::class),
            ServiceTypeEnum::OTHER_SERVICE => $this->container->make(Editor\OtherService::class),
            default => throw new \Exception('Unknown service details')
        };
    }
}
