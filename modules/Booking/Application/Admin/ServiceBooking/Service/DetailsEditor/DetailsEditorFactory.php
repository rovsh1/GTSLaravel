<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor\CIPRoomInAirport;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor\HotelBooking;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor\TransferFromAirport;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor\TransferToAirport;
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
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->module->make(TransferToAirport::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->module->make(TransferFromAirport::class),
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->module->make(CIPRoomInAirport::class),
            ServiceTypeEnum::HOTEL_BOOKING => $this->module->make(HotelBooking::class),
            default => throw new \Exception('Unknown service details')
        };
    }
}
