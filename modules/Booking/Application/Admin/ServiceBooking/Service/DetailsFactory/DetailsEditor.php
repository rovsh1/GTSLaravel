<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsFactory;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsFactory\Editor\CIPRoomInAirport;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsFactory\Editor\TransferFromAirport;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsFactory\Editor\TransferToAirport;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class DetailsEditor
{
    public function __construct(
        private readonly ModuleInterface $module,
        private readonly BookingRepositoryInterface $bookingRepository,
    ) {}

    public function create(
        BookingId $bookingId,
        ServiceTypeEnum $serviceType,
        ServiceId $serviceId,
        array $detailsData
    ): ServiceDetailsInterface {
        $editor = $this->getEditor($serviceType);

        return $editor->create($bookingId, $serviceId, $detailsData);
    }

    public function update(BookingId $bookingId, array $detailsData): void
    {
        $booking = $this->bookingRepository->find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $editor = $this->getEditor($booking->serviceType());

        $editor->update($bookingId, $detailsData);
    }

    private function getEditor(ServiceTypeEnum $serviceType): EditorInterface
    {
        return match ($serviceType) {
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->module->make(TransferToAirport::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->module->make(TransferFromAirport::class),
            ServiceTypeEnum::CIP_IN_AIRPORT => $this->module->make(CIPRoomInAirport::class),
            default => throw new \Exception('Unknown service details')
        };
    }
}
