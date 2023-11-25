<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory;

use Module\Booking\Shared\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Shared\Domain\Booking\Entity\DetailsInterface;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Shared\Domain\Booking\Entity\Other;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToRailway;
use Sdk\Shared\Enum\ServiceTypeEnum;

class DetailsDeserializer
{
    public function deserialize(ServiceTypeEnum $serviceType, array $payload): DetailsInterface
    {
        return match ($serviceType) {
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => CarRentWithDriver::deserialize($payload),
            ServiceTypeEnum::CIP_MEETING_IN_AIRPORT => CIPMeetingInAirport::deserialize($payload),
            ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT => CIPSendoffInAirport::deserialize($payload),
            ServiceTypeEnum::DAY_CAR_TRIP => DayCarTrip::deserialize($payload),
            ServiceTypeEnum::HOTEL_BOOKING => HotelBooking::deserialize($payload),
            ServiceTypeEnum::INTERCITY_TRANSFER => IntercityTransfer::deserialize($payload),
            ServiceTypeEnum::OTHER_SERVICE => Other::deserialize($payload),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => TransferToRailway::deserialize($payload),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => TransferFromRailway::deserialize($payload),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => TransferFromAirport::deserialize($payload),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => TransferToAirport::deserialize($payload),
        };
    }
}
