<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service;

use Illuminate\Support\Collection;
use Module\Booking\Shared\Domain\Shared\Service\DetailOptionDto;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\Entity\Details\CIPMeetingInAirport;
use Sdk\Booking\Entity\Details\CIPSendoffInAirport;
use Sdk\Booking\Entity\Details\DayCarTrip;
use Sdk\Booking\Entity\Details\IntercityTransfer;
use Sdk\Booking\Entity\Details\TransferFromAirport;
use Sdk\Booking\Entity\Details\TransferFromRailway;
use Sdk\Booking\Entity\Details\TransferToAirport;
use Sdk\Booking\Entity\Details\TransferToRailway;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\RailwayStationId;
use Sdk\Shared\Contracts\Adapter\AirportAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CityAdapterInterface;
use Sdk\Shared\Contracts\Adapter\RailwayStationAdapterInterface;

class DetailOptionsDataFactory
{
    public function __construct(
        private readonly AirportAdapterInterface $airportAdapter,
        private readonly RailwayStationAdapterInterface $railwayStationAdapter,
        private readonly CityAdapterInterface $cityAdapter,
    ) {}

    public function build(DetailsInterface $details): Collection
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
            DetailOptionDto::createDate('Дата начала аренды', $details->bookingPeriod()?->dateFrom(), 'date'),
            DetailOptionDto::createDate('Дата завершения аренды', $details->bookingPeriod()?->dateTo(), 'date'),
            DetailOptionDto::createTime('Время подачи авто', $details->bookingPeriod()?->dateFrom(), 'date'),
            DetailOptionDto::createText('Место подачи авто', $details->meetingAddress(), 'meetingAddress'),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet(), 'meetingTablet'),
        ]);
    }

    private function buildTransferToAirport(TransferToAirport $details): Collection
    {
        $cityName = $this->getAirportCityName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата вылета', $details->departureDate(), 'date'),
            DetailOptionDto::createTime('Время вылета', $details->departureDate(), 'date'),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber(), 'flightNumber'),
            DetailOptionDto::createText('Город вылета', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet(), 'meetingTablet'),
        ]);
    }

    private function buildTransferFromAirport(TransferFromAirport $details): Collection
    {
        $cityName = $this->getAirportCityName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата прилёта', $details->arrivalDate(), 'date'),
            DetailOptionDto::createTime('Время прилёта', $details->arrivalDate(), 'date'),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber(), 'flightNumber'),
            DetailOptionDto::createText('Город прилёта', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet(), 'meetingTablet'),
        ]);
    }

    private function buildTransferToRailway(TransferToRailway $details): Collection
    {
        $cityName = $this->getRailwayStationCityName($details->railwayStationId());

        return collect([
            DetailOptionDto::createDate('Дата отправления', $details->departureDate(), 'date'),
            DetailOptionDto::createTime('Время отправления', $details->departureDate(), 'date'),
            DetailOptionDto::createText('Номер поезда', $details->trainNumber(), 'trainNumber'),
            DetailOptionDto::createText('Город отправления', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet(), 'meetingTablet'),
        ]);
    }

    private function buildTransferFromRailway(TransferFromRailway $details): Collection
    {
        $cityName = $this->getRailwayStationCityName($details->railwayStationId());

        return collect([
            DetailOptionDto::createDate('Дата прибытия', $details->arrivalDate(), 'date'),
            DetailOptionDto::createTime('Время прибытия', $details->arrivalDate(), 'date'),
            DetailOptionDto::createText('Номер поезда', $details->trainNumber(), 'trainNumber'),
            DetailOptionDto::createText('Город прибытия', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet(), 'meetingTablet'),
        ]);
    }

    private function buildIntercityTransfer(IntercityTransfer $details): Collection
    {
        return collect([
            DetailOptionDto::createDate('Дата выезда', $details->departureDate(), 'date'),
            DetailOptionDto::createTime('Время выезда', $details->departureDate(), 'date'),
        ]);
    }

    private function buildDayCarTrip(DayCarTrip $details): Collection
    {
        return collect([
            DetailOptionDto::createDate('Дата выезда', $details->departureDate(), 'date'),
            DetailOptionDto::createTime('Время выезда', $details->departureDate(), 'date'),
        ]);
    }

    private function buildCIPMeetingInAirport(CIPMeetingInAirport $details): Collection
    {
        $airportName = $this->getAirportName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата прилёта', $details->arrivalDate(), 'date'),
            DetailOptionDto::createTime('Время прилёта', $details->arrivalDate(), 'date'),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber(), 'flightNumber'),
            DetailOptionDto::createText('Аэропорт', $airportName),
        ]);
    }

    private function buildCIPSendoffInAirport(CIPSendoffInAirport $details): Collection
    {
        $airportName = $this->getAirportName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата прилёта', $details->departureDate(), 'date'),
            DetailOptionDto::createTime('Время прилёта', $details->departureDate(), 'date'),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber(), 'flightNumber'),
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
