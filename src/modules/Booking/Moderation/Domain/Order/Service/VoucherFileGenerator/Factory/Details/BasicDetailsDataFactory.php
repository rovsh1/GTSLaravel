<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\Details;

use Illuminate\Support\Collection;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\Service\PriceDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\ServiceInfoDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\BookingPeriodDataFactory;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\CancelConditionsDataFactory;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\GuestDataFactory;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Shared\Support\Dto\DetailOptionDto;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\Entity\Details\CIPMeetingInAirport;
use Sdk\Booking\Entity\Details\CIPSendoffInAirport;
use Sdk\Booking\Entity\Details\DayCarTrip;
use Sdk\Booking\Entity\Details\IntercityTransfer;
use Sdk\Booking\Entity\Details\Other;
use Sdk\Booking\Entity\Details\TransferFromAirport;
use Sdk\Booking\Entity\Details\TransferFromRailway;
use Sdk\Booking\Entity\Details\TransferToAirport;
use Sdk\Booking\Entity\Details\TransferToRailway;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\RailwayStationId;
use Sdk\Shared\Contracts\Adapter\AirportAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CityAdapterInterface;
use Sdk\Shared\Contracts\Adapter\RailwayStationAdapterInterface;

class BasicDetailsDataFactory
{
    public function __construct(
        private readonly AirportAdapterInterface $airportAdapter,
        private readonly RailwayStationAdapterInterface $railwayStationAdapter,
        private readonly CityAdapterInterface $cityAdapter,
        private readonly BookingPeriodDataFactory $bookingPeriodDataFactory,
        private readonly GuestDataFactory $guestDataFactory,
        private readonly CancelConditionsDataFactory $cancelConditionsDataFactory,
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function build(Booking $booking, DetailsInterface $details): ServiceInfoDto
    {
        $service = $this->supplierAdapter->findService($details->serviceInfo()->id());

        return new ServiceInfoDto(
            title: $service->title,
            detailOptions: $this->buildDetails($details),
            guests: method_exists($details, 'guestIds')
                ? $this->guestDataFactory->build($details->guestIds())
                : [],
            price: $this->buildPrice($booking),
            status: $booking->status()->value()->name,//@todo статус
            cancelConditions: $this->cancelConditionsDataFactory->build($booking->cancelConditions()),
        );
    }

    private function buildPrice(Booking $booking): PriceDto
    {
        $clientPrice = $booking->prices()->clientPrice();
        $amount = $clientPrice->manualValue() ?? $clientPrice->calculatedValue();
        $price = new PriceDto(
            1,
            $amount,
            $amount,
            $clientPrice->currency()->name,
            $clientPrice->penaltyValue(),
        );

        return $price;
    }

    private function buildDetails(DetailsInterface $details): Collection
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
        } elseif ($details instanceof Other) {
            return $this->buildOtherService($details);
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

    private function buildOtherService(Other $details): Collection
    {
        return collect([
            DetailOptionDto::createText('Примечание', $details->description()),
            DetailOptionDto::createDate('Дата', $details->serviceDate()),
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
