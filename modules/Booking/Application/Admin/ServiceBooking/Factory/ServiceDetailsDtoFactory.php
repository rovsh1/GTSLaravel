<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory;

use Module\Booking\Application\Admin\HotelBooking\Dto\Details\AdditionalInfo\ExternalNumberDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\BookingPeriodDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\HotelInfoDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CIPRoomInAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\HotelBookingDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\ServiceDetailsDtoInterface;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferFromAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferToAirportDto;
use Module\Booking\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;

class ServiceDetailsDtoFactory
{
    public function createFromEntity(ServiceDetailsInterface $details): ServiceDetailsDtoInterface
    {
        if ($details instanceof TransferToAirport) {
            return $this->buildTransferToAirport($details);
        } elseif ($details instanceof TransferFromAirport) {
            return $this->buildTransferFromAirport($details);
        } elseif ($details instanceof CIPRoomInAirport) {
            return $this->buildCIPRoomInAirport($details);
        } elseif ($details instanceof HotelBooking) {
            return $this->buildHotelBooking($details);
        } else {
            throw new \Exception('Service details dto not implemented');
        }
    }

    private function buildHotelBooking(HotelBooking $details): HotelBookingDto
    {
        return new HotelBookingDto(
            $details->id()->value(),
            HotelInfoDto::fromDomain($details->hotelInfo()),
            BookingPeriodDto::fromDomain($details->bookingPeriod()),
            $details->roomBookings()->all(),
            $details->externalNumber()
                ? ExternalNumberDto::fromDomain($details->externalNumber())
                : null,
            $details->quotaProcessingMethod()
        );
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
