<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory;

use App\Admin\Models\Reference\City;
use App\Admin\Models\Reference\RailwayStation;
use Module\Booking\Application\Admin\ServiceBooking\Dto\AirportInfoDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CarRentWithDriver\BookingPeriodDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CarRentWithDriverDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CIPRoomInAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CityInfoDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\DayCarTripDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\IntercityTransferDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\OtherServiceDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\RailwayStationInfoDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\ServiceDetailsDtoInterface;
use Module\Booking\Application\Admin\ServiceBooking\Dto\ServiceInfoDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferFromAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferFromRailwayDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferToAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferToRailwayDto;
use Module\Booking\Application\Admin\ServiceBooking\Factory\HotelBooking\DetailsDtoFactory as HotelDetailsDtoFactory;
use Module\Booking\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Domain\Booking\Entity\OtherService;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Domain\BookingRequest\Adapter\AirportAdapterInterface;
use Module\Booking\Domain\BookingRequest\Adapter\CityAdapterInterface;
use Module\Booking\Domain\BookingRequest\Adapter\RailwayStationAdapterInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Module\Booking\Infrastructure\AirportBooking\Models\Airport;

class ServiceDetailsDtoFactory
{
    public function __construct(
        private readonly HotelDetailsDtoFactory $hotelFactory,
        private readonly CarBidDtoFactory $carBidFactory,
        private readonly AirportAdapterInterface $airportAdapter,
        private readonly RailwayStationAdapterInterface $railwayStationAdapter,
        private readonly CityAdapterInterface $cityAdapter,
    ) {}

    public function createFromEntity(ServiceDetailsInterface $details): ServiceDetailsDtoInterface
    {
        if ($details instanceof TransferToAirport) {
            return $this->buildTransferToAirport($details);
        } elseif ($details instanceof TransferFromAirport) {
            return $this->buildTransferFromAirport($details);
        } elseif ($details instanceof CIPRoomInAirport) {
            return $this->buildCIPRoomInAirport($details);
        } elseif ($details instanceof HotelBooking) {
            return $this->hotelFactory->build($details);
        } elseif ($details instanceof CarRentWithDriver) {
            return $this->buildCarRentWithDriver($details);
        } elseif ($details instanceof IntercityTransfer) {
            return $this->buildIntercityTransfer($details);
        } elseif ($details instanceof DayCarTrip) {
            return $this->buildDayCarTrip($details);
        } elseif ($details instanceof TransferToRailway) {
            return $this->buildTransferToRailway($details);
        } elseif ($details instanceof TransferFromRailway) {
            return $this->buildTransferFromRailway($details);
        } elseif ($details instanceof OtherService) {
            return $this->buildOtherService($details);
        } else {
            throw new \Exception('Service details dto not implemented');
        }
    }

    private function buildTransferToAirport(TransferToAirport $details): TransferToAirportDto
    {
        return new TransferToAirportDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildAirportInfo($details->airportId()->value()),
            $details->flightNumber(),
            $details->meetingTablet(),
            $details->departureDate()?->format(DATE_ATOM),
            $this->carBidFactory->build($details->serviceInfo()->supplierId(), $details->carBids())
        );
    }

    private function buildTransferFromAirport(TransferFromAirport $details): TransferFromAirportDto
    {
        return new TransferFromAirportDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildAirportInfo($details->airportId()->value()),
            $details->flightNumber(),
            $details->meetingTablet(),
            $details->arrivalDate()?->format(DATE_ATOM),
            $this->carBidFactory->build($details->serviceInfo()->supplierId(), $details->carBids())
        );
    }

    private function buildTransferToRailway(TransferToRailway $details): TransferToRailwayDto
    {
        return new TransferToRailwayDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildRailwayStationInfo($details->railwayStationId()->value()),
            $details->trainNumber(),
            $details->meetingTablet(),
            $details->departureDate()?->format(DATE_ATOM),
            $this->carBidFactory->build($details->serviceInfo()->supplierId(), $details->carBids())
        );
    }

    private function buildTransferFromRailway(TransferFromRailway $details): TransferFromRailwayDto
    {
        return new TransferFromRailwayDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildRailwayStationInfo($details->railwayStationId()->value()),
            $details->trainNumber(),
            $details->meetingTablet(),
            $details->arrivalDate()?->format(DATE_ATOM),
            $this->carBidFactory->build($details->serviceInfo()->supplierId(), $details->carBids())
        );
    }

    private function buildCIPRoomInAirport(CIPRoomInAirport $details): CIPRoomInAirportDto
    {
        return new CIPRoomInAirportDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildAirportInfo($details->airportId()->value()),
            $details->flightNumber(),
            $details->serviceDate()?->format(DATE_ATOM),
            $details->guestIds()->map(fn(GuestId $id) => $id->value()),
        );
    }

    private function buildCarRentWithDriver(CarRentWithDriver $details): CarRentWithDriverDto
    {
        $bookingPeriod = null;
        if ($details->bookingPeriod() !== null) {
            $bookingPeriod = new BookingPeriodDto(
                $details->bookingPeriod()->dateFrom()->format(DATE_ATOM),
                $details->bookingPeriod()->dateTo()->format(DATE_ATOM),
            );
        }

        return new CarRentWithDriverDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildCityInfo($details->cityId()->value()),
            $details->meetingAddress(),
            $details->meetingTablet(),
            $bookingPeriod,
            $this->carBidFactory->build($details->serviceInfo()->supplierId(), $details->carBids())
        );
    }

    private function buildIntercityTransfer(IntercityTransfer $details): IntercityTransferDto
    {
        return new IntercityTransferDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildCityInfo($details->fromCityId()->value()),
            $this->buildCityInfo($details->toCityId()->value()),
            $details->isReturnTripIncluded(),
            $details->departureDate()?->format(DATE_ATOM),
            $this->carBidFactory->build($details->serviceInfo()->supplierId(), $details->carBids())
        );
    }

    private function buildDayCarTrip(DayCarTrip $details): DayCarTripDto
    {
        return new DayCarTripDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildCityInfo($details->cityId()->value()),
            $details->departureDate()?->format(DATE_ATOM),
            $details->destinationsDescription(),
            $this->carBidFactory->build($details->serviceInfo()->supplierId(), $details->carBids())
        );
    }

    private function buildOtherService(OtherService $details): OtherServiceDto
    {
        return new OtherServiceDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $details->description()
        );
    }

    private function buildServiceInfoDto(ServiceInfo $serviceInfo): ServiceInfoDto
    {
        return new ServiceInfoDto(
            $serviceInfo->id(),
            $serviceInfo->title(),
            $serviceInfo->supplierId(),
        );
    }

    private function buildAirportInfo(int $airportId): AirportInfoDto
    {
        return $this->airportAdapter->find($airportId);
    }

    private function buildRailwayStationInfo(int $stationId): RailwayStationInfoDto
    {
        return $this->railwayStationAdapter->find($stationId);
    }

    private function buildCityInfo(int $cityId): CityInfoDto
    {
        return $this->cityAdapter->find($cityId);
    }
}
