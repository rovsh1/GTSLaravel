<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Factory;

use Module\Booking\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Entity\OtherService;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Domain\Booking\ValueObject\AirportId;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\ExternalNumber;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingIdCollection;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Airport;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Hotel;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Other;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Transfer;
use Module\Shared\Enum\ServiceTypeEnum;

class DetailsFactory
{
    public function buildByBooking(Booking $booking): ServiceDetailsInterface
    {
        return match ($booking->service_type) {
            ServiceTypeEnum::HOTEL_BOOKING => $this->buildHotelBookingDetails($booking->hotelDetails),
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->buildAirportDetails($booking->airportDetails),
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->buildCarRentDetails($booking->transferDetails),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->buildTransferToAirportDetails($booking->transferDetails),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->buildTransferFromAirportDetails($booking->transferDetails),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->buildTransferToRailwayDetails($booking->transferDetails),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->buildTransferFromRailwayDetails($booking->transferDetails),
            ServiceTypeEnum::OTHER_SERVICE => $this->buildOtherDetails($booking->otherDetails),
            default => throw new \Exception('Unknown Booking service type')
        };
    }

    public function build(DetailsModelInterface $model): ServiceDetailsInterface
    {
        return match ($model->serviceType()) {
            ServiceTypeEnum::HOTEL_BOOKING => $this->buildHotelBookingDetails($model),
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->buildAirportDetails($model),
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->buildCarRentDetails($model),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->buildTransferToAirportDetails($model),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->buildTransferFromAirportDetails($model),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->buildTransferToRailwayDetails($model),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->buildTransferFromRailwayDetails($model),
            ServiceTypeEnum::OTHER_SERVICE => $this->buildOtherDetails($model),
            default => throw new \Exception('Unknown Booking service type')
        };
    }

    private function buildHotelBookingDetails(Hotel $details): HotelBooking
    {
        $detailsData = $details->data;

        $externalNumberData = $detailsData['externalNumber'] ?? null;

        return new HotelBooking(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            hotelInfo: HotelInfo::fromData($detailsData['hotelInfo']),
            bookingPeriod: BookingPeriod::fromData($detailsData['period']),
            roomBookings: RoomBookingIdCollection::fromData($detailsData['room_booking_ids']),
            externalNumber: $externalNumberData ? ExternalNumber::fromData($externalNumberData) : null,
            quotaProcessingMethod: $details->quota_processing_method,
        );
    }

    private function buildAirportDetails(Airport $details): CIPRoomInAirport
    {
        $detailsData = $details->data;

        return new CIPRoomInAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'],
            serviceDate: $details->date,
            guestIds: GuestIdCollection::fromData($detailsData['guestIds']),
        );
    }

    private function buildTransferToAirportDetails(Transfer $details): TransferToAirport
    {
        $detailsData = $details->data;

        return new TransferToAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'] ?? null,
            meetingTablet: $detailsData['meetingTablet'] ?? null,
            departureDate: $details->date_start,
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }

    private function buildTransferFromAirportDetails(Transfer $details): TransferFromAirport
    {
        $detailsData = $details->data;

        return new TransferFromAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'] ?? null,
            meetingTablet: $detailsData['meetingTablet'] ?? null,
            arrivalDate: $details->date_start,
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }

    private function buildTransferToRailwayDetails(Transfer $details): mixed
    {
        throw new \Exception('Not implemented');
    }

    private function buildTransferFromRailwayDetails(Transfer $details): mixed
    {
        throw new \Exception('Not implemented');
    }

    private function buildCarRentDetails(Transfer $details): mixed
    {
        throw new \Exception('Not implemented');
    }

    private function buildOtherDetails(Other $details): OtherService
    {
        return new OtherService(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            serviceInfo: $this->buildServiceInfo($details->data['serviceInfo']),
            description: $details->data['description']
        );
    }

    private function buildServiceInfo(array $data): ServiceInfo
    {
        return new ServiceInfo(
            $data['serviceId'],
            $data['title'],
            $data['supplierId'],
        );
    }
}
