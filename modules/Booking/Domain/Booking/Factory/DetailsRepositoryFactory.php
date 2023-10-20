<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Factory;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\TransferToAirportRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\ModuleInterface;

class DetailsRepositoryFactory
{
    public function __construct(
        private readonly ModuleInterface $module,
        private readonly BookingRepositoryInterface $bookingRepository
    ) {}

    public function buildByBookingId(BookingId $bookingId): mixed
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);

        return $this->build($booking);
    }

    public function build(Booking $booking): mixed
    {
        return match ($booking->serviceType()) {
            ServiceTypeEnum::HOTEL_BOOKING => $this->module->make(HotelBookingRepositoryInterface::class),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->module->make(TransferToAirportRepositoryInterface::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->module->make(TransferFromAirportRepositoryInterface::class),
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->module->make(CIPRoomInAirportRepositoryInterface::class),
            default => throw new \RuntimeException('Service type repository not implemented'),
        };
    }
}
