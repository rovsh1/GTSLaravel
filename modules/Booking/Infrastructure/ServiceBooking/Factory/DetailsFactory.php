<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Factory;

use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\ServiceBooking\Entity\OtherService;
use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport;
use Module\Booking\Domain\ServiceBooking\ValueObject\AirportId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\CarBidCollection;
use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Airport;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Other;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Transfer;
use Module\Shared\Enum\ServiceTypeEnum;

class DetailsFactory
{
    public function buildByBooking(Booking $booking): ServiceDetailsInterface
    {
        return match ($booking->service_type) {
            ServiceTypeEnum::CIP_IN_AIRPORT => $this->buildAirportDetails($booking->airportDetails),
            ServiceTypeEnum::CAR_RENT => $this->buildCarRentDetails($booking->transferDetails),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->buildTransferToAirportDetails($booking->transferDetails),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->buildTransferFromAirportDetails($booking->transferDetails),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->buildTransferToRailwayDetails($booking->transferDetails),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->buildTransferFromRailwayDetails($booking->transferDetails),
            ServiceTypeEnum::OTHER => $this->buildOtherDetails($booking->otherDetails),
            default => throw new \Exception('Unknown Booking service type')
        };
    }

    public function build(DetailsModelInterface $model): ServiceDetailsInterface
    {
        return match ($model->serviceType()) {
            ServiceTypeEnum::CIP_IN_AIRPORT => $this->buildAirportDetails($model),
            ServiceTypeEnum::CAR_RENT => $this->buildCarRentDetails($model),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->buildTransferToAirportDetails($model),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->buildTransferFromAirportDetails($model),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->buildTransferToRailwayDetails($model),
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->buildTransferFromRailwayDetails($model),
            ServiceTypeEnum::OTHER => $this->buildOtherDetails($model),
            default => throw new \Exception('Unknown Booking service type')
        };
    }

    private function buildAirportDetails(Airport $details): CIPRoomInAirport
    {
        $detailsData = $details->data;

        return new CIPRoomInAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            serviceTitle: $detailsData['serviceTitle'],
            airportId: new AirportId($details->airport_id),
            flightNumber: $details->data['flightNumber'],
            serviceDate: $details->date,
            guestIds: GuestIdCollection::fromData($detailsData['guest_ids']),
        );
    }

    private function buildTransferToAirportDetails(Transfer $details): TransferToAirport
    {
        $detailsData = $details->data;

        return new TransferToAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            serviceTitle: $detailsData['serviceTitle'],
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'],
            departureDate: $details->date,
            carBids: new CarBidCollection()
        );
    }

    private function buildTransferFromAirportDetails(Transfer $details): TransferFromAirport
    {
        $detailsData = $details->data;

        return new TransferFromAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->bookingId()),
            serviceTitle: $detailsData['serviceTitle'],
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'],
            meetingTablet: $detailsData['meetingTablet'],
            arrivalDate: $details->date,
            carBids: new CarBidCollection()
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
            serviceTitle: $details->data['serviceTitle'],
            description: $details->data['description']
        );
    }

}
