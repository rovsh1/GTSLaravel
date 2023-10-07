<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\ServiceBooking\Entity\OtherService;
use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport;
use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ServiceBooking as Entity;
use Module\Booking\Domain\ServiceBooking\ValueObject\AirportId;
use Module\Booking\Domain\ServiceBooking\ValueObject\CarBidCollection;
use Module\Booking\Domain\ServiceBooking\ValueObject\DetailsId;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking as Model;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Airport;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Other;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Transfer;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class BookingRepository implements BookingRepositoryInterface
{
    protected function getModel(): string
    {
        return Model::class;
    }

    public function find(int $id): ?Entity
    {
        $model = $this->getModel()::find($id);
        if ($model === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->buildEntityFromModel($model);
    }

    public function query(): Builder
    {
        // TODO: Implement query() method.
    }

    public function delete(BookingInterface|Entity $booking): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function bulkDelete(array $ids): void
    {
        // TODO: Implement bulkDelete() method.
    }

    private function buildEntityFromModel(Model $booking): Entity
    {
        $serviceDetails = $this->buildDetails($booking);

        return new Entity(
            id: new BookingId($booking->id),
            orderId: new OrderId($booking->order_id),
            status: $booking->status,
            createdAt: $booking->created_at->toImmutable(),
            creatorId: new CreatorId($booking->creator_id),
            price: BookingPrice::fromData($booking->price),
            cancelConditions: $booking->cancel_conditions !== null
                ? CancelConditions::fromData($booking->cancel_conditions)
                : null,
            note: $booking->note,
            serviceDetails: $serviceDetails,
        );
    }

    private function buildDetails(Model $booking): ServiceDetailsInterface
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

    private function buildAirportDetails(Airport $details): CIPRoomInAirport
    {
        $detailsData = $details->data;

        return new CIPRoomInAirport(
            id: new DetailsId($details->id),
            airportId: new AirportId($details->airport_id),
            flightNumber: $details->data['flightNumber'],
            guestIds: GuestIdCollection::fromData($detailsData['guest_ids']),
            serviceDate: $details->date,
        );
    }

    private function buildTransferToAirportDetails(Transfer $details): TransferToAirport
    {
        $detailsData = $details->data;

        return new TransferToAirport(
            id: new DetailsId($details->id),
            airportId: $detailsData['airportId'],
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
            airportId: $detailsData['airportId'],
            flightNumber: $detailsData['flightNumber'],
            meetingTablet: $detailsData['meetingTablet'],
            arrivalDate: $details->date,
            carBids: new CarBidCollection()
        );
    }

    private function buildTransferToRailwayDetails(Transfer $details): mixed {}

    private function buildTransferFromRailwayDetails(Transfer $details): mixed {}

    private function buildCarRentDetails(Transfer $details): mixed {}

    private function buildOtherDetails(Other $details): OtherService
    {
        return new OtherService(
            id: new DetailsId($details->id),
            description: $details->data['description']
        );
    }
}
