<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\Processor\CIPRoomInAirport;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\Processor\TransferFromAirport;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\Processor\TransferToAirport;
use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceId;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\ModuleInterface;

class DetailsCreator
{
    public function __construct(
        private readonly ModuleInterface $module
    ) {}

    public function create(
        BookingId $bookingId,
        ServiceTypeEnum $serviceType,
        ServiceId $serviceId,
        array $detailsData
    ): ServiceDetailsInterface {
        $processor = $this->getProcessor($serviceType);

        return $processor->process($bookingId, $serviceId, $detailsData);
    }

    private function getProcessor(ServiceTypeEnum $serviceType): ProcessorInterface
    {
        return match ($serviceType) {
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->module->make(TransferToAirport::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->module->make(TransferFromAirport::class),
            ServiceTypeEnum::CIP_IN_AIRPORT => $this->module->make(CIPRoomInAirport::class),
            default => throw new \Exception('Unknown service details')
        };
    }
}
