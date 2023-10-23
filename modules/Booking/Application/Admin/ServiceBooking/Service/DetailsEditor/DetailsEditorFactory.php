<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor;

use Module\Booking\Domain\Booking\Booking;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\ModuleInterface;

class DetailsEditorFactory
{
    public function __construct(
        private readonly ModuleInterface $module,
    ) {}

    public function build(Booking $booking): EditorInterface
    {
        return match ($booking->serviceType()) {
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->module->make(Editor\CarRentWithDriver::class),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->module->make(Editor\TransferToAirport::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->module->make(Editor\TransferFromAirport::class),
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->module->make(Editor\CIPRoomInAirport::class),
            ServiceTypeEnum::HOTEL_BOOKING => $this->module->make(Editor\HotelBooking::class),
            ServiceTypeEnum::INTERCITY_TRANSFER => $this->module->make(Editor\IntercityTransfer::class),
            ServiceTypeEnum::DAY_CAR_TRIP => $this->module->make(Editor\DayCarTrip::class),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->module->make(Editor\TransferFromRailway::class),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->module->make(Editor\TransferToRailway::class),
            ServiceTypeEnum::OTHER_SERVICE => $this->module->make(Editor\OtherService::class),
            default => throw new \Exception('Unknown service details')
        };
    }
}
