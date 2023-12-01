<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\Entity\Details\CIPMeetingInAirport;
use Sdk\Booking\Entity\Details\CIPSendoffInAirport;
use Sdk\Booking\Entity\Details\DayCarTrip;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Entity\Details\IntercityTransfer;
use Sdk\Booking\Entity\Details\Other;
use Sdk\Booking\Entity\Details\TransferFromAirport;
use Sdk\Booking\Entity\Details\TransferFromRailway;
use Sdk\Booking\Entity\Details\TransferToAirport;
use Sdk\Booking\Entity\Details\TransferToRailway;
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
