<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory;

use Module\Booking\Shared\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Shared\Domain\Booking\Entity\Other;
use Module\Booking\Shared\Domain\Booking\Entity\DetailsInterface;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToRailway;
use Module\Shared\Enum\ServiceTypeEnum;

class DetailsDeserializer
{
    public function deserialize(ServiceTypeEnum $serviceType, array $payload): DetailsInterface
    {
        return match ($serviceType) {
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => CarRentWithDriver::fromData($payload),
            ServiceTypeEnum::CIP_MEETING_IN_AIRPORT => CIPMeetingInAirport::fromData($payload),
            ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT => CIPSendoffInAirport::fromData($payload),
            ServiceTypeEnum::DAY_CAR_TRIP => DayCarTrip::fromData($payload),
            ServiceTypeEnum::HOTEL_BOOKING => HotelBooking::fromData($payload),
            ServiceTypeEnum::INTERCITY_TRANSFER => IntercityTransfer::fromData($payload),
            ServiceTypeEnum::OTHER_SERVICE => Other::fromData($payload),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => TransferToRailway::fromData($payload),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => TransferFromRailway::fromData($payload),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => TransferFromAirport::fromData($payload),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => TransferToAirport::fromData($payload),
        };
    }
}
