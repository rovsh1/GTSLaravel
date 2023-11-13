<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service;

use Illuminate\Support\Collection;
use Module\Booking\Shared\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Shared\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Shared\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Shared\Domain\Booking\Service\Dto\DetailOptionDto;
use Module\Booking\Shared\Domain\Booking\ValueObject\AirportId;
use Module\Booking\Shared\Domain\Booking\ValueObject\RailwayStationId;
use Module\Shared\Contracts\Adapter\AirportAdapterInterface;
use Module\Shared\Contracts\Adapter\CityAdapterInterface;
use Module\Shared\Contracts\Adapter\RailwayStationAdapterInterface;

class DetailOptionsDataFactory
{
    public function __construct(
        private readonly AirportAdapterInterface $airportAdapter,
        private readonly RailwayStationAdapterInterface $railwayStationAdapter,
        private readonly CityAdapterInterface $cityAdapter,
    ) {}

    public function build(ServiceDetailsInterface $details): Collection
    {
        if ($details instanceof CarRentWithDriver) {
            return $this->buildCarRentWithDriver($details);
        } elseif ($details instanceof TransferToAirport) {
            return $this->buildTransferToAirport($details);
        } elseif ($details instanceof TransferFromAirport) {
            return $this->buildTransferFromAirport($details);
        } elseif ($details instanceof TransferToRailway) {
            return $this->buildTransferToRailway($details);
        } elseif ($details instanceof TransferFromRailway) {
            return $this->buildTransferFromRailway($details);
        } elseif ($details instanceof IntercityTransfer) {
            return $this->buildIntercityTransfer($details);
        } elseif ($details instanceof DayCarTrip) {
            return $this->buildDayCarTrip($details);
        } elseif ($details instanceof CIPMeetingInAirport) {
            return $this->buildCIPMeetingInAirport($details);
        } elseif ($details instanceof CIPSendoffInAirport) {
            return $this->buildCIPSendoffInAirport($details);
        }
        throw new \RuntimeException('Unknown details type');
    }

    /**
     * @param CarRentWithDriver $details
     * @return Collection<int, DetailOptionDto>
     */
    private function buildCarRentWithDriver(CarRentWithDriver $details): Collection
    {
        return collect([
            DetailOptionDto::createDate('Дата начала аренды', $details->bookingPeriod()?->dateFrom()),
            DetailOptionDto::createDate('Дата завершения аренды', $details->bookingPeriod()?->dateTo()),
            DetailOptionDto::createTime('Время подачи авто', $details->bookingPeriod()?->dateFrom()),
            DetailOptionDto::createText('Место подачи авто', $details->meetingAddress()),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferToAirport(TransferToAirport $details): Collection
    {
        $cityName = $this->getAirportCityName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата вылета', $details->departureDate()),
            DetailOptionDto::createTime('Время вылета', $details->departureDate()),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber()),
            DetailOptionDto::createText('Город вылета', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferFromAirport(TransferFromAirport $details): Collection
    {
        $cityName = $this->getAirportCityName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата прилёта', $details->arrivalDate()),
            DetailOptionDto::createTime('Время прилёта', $details->arrivalDate()),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber()),
            DetailOptionDto::createText('Город прилёта', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferToRailway(TransferToRailway $details): Collection
    {
        $cityName = $this->getRailwayStationCityName($details->railwayStationId());

        return collect([
            DetailOptionDto::createDate('Дата отправления', $details->departureDate()),
            DetailOptionDto::createTime('Время отправления', $details->departureDate()),
            DetailOptionDto::createText('Номер поезда', $details->trainNumber()),
            DetailOptionDto::createText('Город отправления', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferFromRailway(TransferFromRailway $details): Collection
    {
        $cityName = $this->getRailwayStationCityName($details->railwayStationId());

        return collect([
            DetailOptionDto::createDate('Дата прибытия', $details->arrivalDate()),
            DetailOptionDto::createTime('Время прибытия', $details->arrivalDate()),
            DetailOptionDto::createText('Номер поезда', $details->trainNumber()),
            DetailOptionDto::createText('Город прибытия', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildIntercityTransfer(IntercityTransfer $details): Collection
    {
        return collect([
            DetailOptionDto::createDate('Дата выезда', $details->departureDate()),
            DetailOptionDto::createTime('Время выезда', $details->departureDate()),
        ]);
    }

    private function buildDayCarTrip(DayCarTrip $details): Collection
    {
        return collect([
            DetailOptionDto::createDate('Дата выезда', $details->departureDate()),
            DetailOptionDto::createTime('Время выезда', $details->departureDate()),
        ]);
    }

    private function buildCIPMeetingInAirport(CIPMeetingInAirport $details): Collection
    {
        $airportName = $this->getAirportName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата прилёта', $details->arrivalDate()),
            DetailOptionDto::createTime('Время прилёта', $details->arrivalDate()),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber()),
            DetailOptionDto::createText('Аэропорт', $airportName),
        ]);
    }

    private function buildCIPSendoffInAirport(CIPSendoffInAirport $details): Collection
    {
        $airportName = $this->getAirportName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата прилёта', $details->departureDate()),
            DetailOptionDto::createTime('Время прилёта', $details->departureDate()),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber()),
            DetailOptionDto::createText('Аэропорт', $airportName),
        ]);
    }


    private function getAirportName(AirportId $airportId): ?string
    {
        $airport = $this->airportAdapter->find($airportId->value());
        if ($airport === null) {
            return null;
        }

        return $airport->name;
    }

    private function getAirportCityName(AirportId $airportId): ?string
    {
        $airport = $this->airportAdapter->find($airportId->value());
        if ($airport === null) {
            return null;
        }
        $city = $this->cityAdapter->find($airport->cityId);

        return $city?->name;
    }

    private function getRailwayStationCityName(RailwayStationId $stationId): ?string
    {
        $railwayStation = $this->railwayStationAdapter->find($stationId->value());
        if ($railwayStation === null) {
            return null;
        }
        $city = $this->cityAdapter->find($railwayStation->cityId);

        return $city?->name;
    }
}
