<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory;

use Module\Booking\Application\Admin\ServiceBooking\Dto\CIPRoomInAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\ServiceDetailsDtoInterface;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferFromAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferToAirportDto;
use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport;
use Module\Shared\Enum\ServiceTypeEnum;

class ServiceDetailsDtoFactory
{
    public function createFromEntity(ServiceDetailsInterface $details): ServiceDetailsDtoInterface
    {
        return match ($details->serviceInfo()->type()) {
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->buildTransferToAirport($details),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->buildTransferFromAirport($details),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->buildTransferToRailway($details),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->buildTransferFromRailway($details),
            ServiceTypeEnum::CAR_RENT => $this->buildCarRent($details),
            ServiceTypeEnum::CIP_IN_AIRPORT => $this->buildCIPRoomInAirport($details),
            default => throw new \Exception('Service details dto not implemented')
        };
    }

    private function buildTransferToAirport(TransferToAirport $details): TransferToAirportDto
    {
        return new TransferToAirportDto(
            $details->id()->value(),
            $details->serviceInfo()->title(),
            $details->airportId()->value(),
            $details->flightNumber(),
            $details->departureDate()?->format(DATE_ATOM),
            $details->carBids()->all()
        );
    }

    private function buildTransferFromAirport(TransferFromAirport $details): TransferFromAirportDto
    {
        return new TransferFromAirportDto();
    }

    private function buildCIPRoomInAirport(CIPRoomInAirport $details): CIPRoomInAirportDto
    {
        return new CIPRoomInAirportDto();
    }

    private function buildTransferToRailway(mixed $details): mixed
    {
        throw new \Exception('Service details dto not implemented');
    }

    private function buildTransferFromRailway(mixed $details): mixed
    {
        throw new \Exception('Service details dto not implemented');
    }

    private function buildCarRent(mixed $details): mixed
    {
        throw new \Exception('Service details dto not implemented');
    }
}
