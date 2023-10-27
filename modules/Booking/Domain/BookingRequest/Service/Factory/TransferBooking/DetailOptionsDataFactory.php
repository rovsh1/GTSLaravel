<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Factory\TransferBooking;

use Format;
use Illuminate\Support\Collection;
use Module\Booking\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Domain\Booking\ValueObject\AirportId;
use Module\Booking\Domain\Booking\ValueObject\RailwayStationId;
use Module\Booking\Domain\BookingRequest\Adapter\AirportAdapterInterface;
use Module\Booking\Domain\BookingRequest\Adapter\CityAdapterInterface;
use Module\Booking\Domain\BookingRequest\Adapter\RailwayStationAdapterInterface;
use Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking\DetailOptionDto;

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
        }
        throw new \RuntimeException('Unknown details type');
    }

    //@todo перенести форматирование в шаблон blade

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
            DetailOptionDto::createDate('Дата вылета', Format::date($details->departureDate())),
            DetailOptionDto::createTime('Время вылета', $details->departureDate()?->format('H:i')),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber()),
            DetailOptionDto::createText('Город вылета', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferFromAirport(TransferFromAirport $details): Collection
    {
        $cityName = $this->getAirportCityName($details->airportId());

        return collect([
            DetailOptionDto::createDate('Дата прилёта', $details->arrivalDate()?->format('d.m.Y')),
            DetailOptionDto::createTime('Время прилёта', $details->arrivalDate()?->format('H:i')),
            DetailOptionDto::createText('Номер рейса', $details->flightNumber()),
            DetailOptionDto::createText('Город прилёта', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferToRailway(TransferToRailway $details): Collection
    {
        $cityName = $this->getRailwayStationCityName($details->railwayStationId());

        return collect([
            DetailOptionDto::createDate('Дата отправления', $details->departureDate()?->format('d.m.Y')),
            DetailOptionDto::createTime('Время отправления', $details->departureDate()?->format('H:i')),
            DetailOptionDto::createText('Номер поезда', $details->trainNumber()),
            DetailOptionDto::createText('Город отправления', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferFromRailway(TransferFromRailway $details): Collection
    {
        $cityName = $this->getRailwayStationCityName($details->railwayStationId());

        return collect([
            DetailOptionDto::createDate('Дата прибытия', $details->arrivalDate()?->format('d.m.Y')),
            DetailOptionDto::createTime('Время прибытия', $details->arrivalDate()?->format('H:i')),
            DetailOptionDto::createText('Номер поезда', $details->trainNumber()),
            DetailOptionDto::createText('Город прибытия', $cityName),
            DetailOptionDto::createText('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildIntercityTransfer(IntercityTransfer $details): Collection
    {
        return collect([
            DetailOptionDto::createDate('Дата выезда', $details->departureDate()?->format('d.m.Y')),
            DetailOptionDto::createTime('Время выезда', $details->departureDate()?->format('H:i')),
        ]);
    }

    private function buildDayCarTrip(DayCarTrip $details): Collection
    {
        return collect([
            DetailOptionDto::createDate('Дата выезда', $details->departureDate()?->format('d.m.Y')),
            DetailOptionDto::createTime('Время выезда', $details->departureDate()?->format('H:i')),
        ]);
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
