<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CarRentWithDriverRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CIPMeetingInAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CIPSendoffInAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\DayCarTripRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\IntercityTransferRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\OtherServiceRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferFromRailwayRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferToAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferToRailwayRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\Support\ContainerInterface;

class DetailsRepositoryFactory
{
    public function __construct(
        private readonly ContainerInterface $container,
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
            ServiceTypeEnum::HOTEL_BOOKING => $this->container->make(HotelBookingRepositoryInterface::class),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->container->make(TransferToAirportRepositoryInterface::class),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->container->make(TransferFromAirportRepositoryInterface::class),
            ServiceTypeEnum::CIP_MEETING_IN_AIRPORT => $this->container->make(CIPMeetingInAirportRepositoryInterface::class),
            ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT => $this->container->make(CIPSendoffInAirportRepositoryInterface::class),
            ServiceTypeEnum::INTERCITY_TRANSFER => $this->container->make(IntercityTransferRepositoryInterface::class),
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->container->make(CarRentWithDriverRepositoryInterface::class),
            ServiceTypeEnum::DAY_CAR_TRIP => $this->container->make(DayCarTripRepositoryInterface::class),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->container->make(TransferToRailwayRepositoryInterface::class),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->container->make(TransferFromRailwayRepositoryInterface::class),
            ServiceTypeEnum::OTHER_SERVICE => $this->container->make(OtherServiceRepositoryInterface::class),
            default => throw new \RuntimeException('Service type repository not implemented'),
        };
    }
}
