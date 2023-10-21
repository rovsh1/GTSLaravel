<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory;

use App\Admin\Models\Reference\City;
use Module\Booking\Application\Admin\ServiceBooking\Dto\AirportInfoDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CarRentWithDriverDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CIPRoomInAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CityInfoDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\IntercityTransferDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\OtherServiceDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\ServiceDetailsDtoInterface;
use Module\Booking\Application\Admin\ServiceBooking\Dto\ServiceInfoDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferFromAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Dto\TransferToAirportDto;
use Module\Booking\Application\Admin\ServiceBooking\Factory\HotelBooking\DetailsDtoFactory as HotelDetailsDtoFactory;
use Module\Booking\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Domain\Booking\Entity\OtherService;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Module\Booking\Infrastructure\AirportBooking\Models\Airport;

class ServiceDetailsDtoFactory
{
    public function __construct(
        private readonly HotelDetailsDtoFactory $hotelFactory,
        private readonly CarBidDtoFactory $carBidFactory,
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

    private function buildTransferToRailway(mixed $details): mixed
    {
        throw new \Exception('Service details dto not implemented');
    }

    private function buildTransferFromRailway(mixed $details): mixed
    {
        throw new \Exception('Service details dto not implemented');
    }

    private function buildCarRentWithDriver(CarRentWithDriver $details): mixed
    {
        return new CarRentWithDriverDto(
            $details->id()->value(),
            $this->buildServiceInfoDto($details->serviceInfo()),
            $this->buildCityInfo($details->cityId()->value()),
            $details->hoursLimit(),
            $details->date()->format(DATE_ATOM),
            $this->carBidFactory->build($details->serviceInfo()->supplierId(), $details->carBids())
        );
    }

    private function buildIntercityTransfer(IntercityTransfer $details): mixed
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

    private function buildOtherService(OtherService $details): mixed
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
        $airport = Airport::find($airportId);

        return new AirportInfoDto(
            $airport->id,
            $airport->name,
        );
    }

    private function buildCityInfo(int $cityId): CityInfoDto
    {
        $city = City::find($cityId);

        return new CityInfoDto(
            $city->id,
            $city->name,
        );
    }
}
