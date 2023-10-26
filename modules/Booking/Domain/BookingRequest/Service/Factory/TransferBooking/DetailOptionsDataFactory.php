<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Factory\TransferBooking;

use Illuminate\Support\Collection;
use Module\Booking\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking\DetailOptionDto;

class DetailOptionsDataFactory
{
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

    /**
     * @param CarRentWithDriver $details
     * @return Collection<int, DetailOptionDto>
     */
    private function buildCarRentWithDriver(CarRentWithDriver $details): Collection
    {
        return collect([
            new DetailOptionDto('Дата начала аренды', $details->date()->format('d.m.Y')),
            new DetailOptionDto('Дата завершения аренды', $details->date()->format('d.m.Y')),
            new DetailOptionDto('Время подачи авто', $details->date()->format('H:i')),
            new DetailOptionDto('Место подачи авто', $details->meetingAddress()),
            new DetailOptionDto('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferToAirport(TransferToAirport $details): Collection
    {
        return collect([
            new DetailOptionDto('Дата вылета', $details->departureDate()?->format('d.m.Y')),
            new DetailOptionDto('Время вылета', $details->departureDate()?->format('H:i')),
            new DetailOptionDto('Номер рейса', $details->flightNumber()),
            new DetailOptionDto('Город вылета', '{получить город вылета}'),
            new DetailOptionDto('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferFromAirport(TransferFromAirport $details): Collection
    {
        return collect([
            new DetailOptionDto('Дата прилёта', $details->arrivalDate()?->format('d.m.Y')),
            new DetailOptionDto('Время прилёта', $details->arrivalDate()?->format('H:i')),
            new DetailOptionDto('Номер рейса', $details->flightNumber()),
            new DetailOptionDto('Город прилёта', '{получить город вылета}'),
            new DetailOptionDto('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferToRailway(TransferToRailway $details): Collection
    {
        return collect([
            new DetailOptionDto('Дата отправления', $details->departureDate()?->format('d.m.Y')),
            new DetailOptionDto('Время отправления', $details->departureDate()?->format('H:i')),
            new DetailOptionDto('Номер поезда', $details->trainNumber()),
            new DetailOptionDto('Город отправления', $details->railwayStationId()),
            new DetailOptionDto('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildTransferFromRailway(TransferFromRailway $details): Collection
    {
        return collect([
            new DetailOptionDto('Дата прибытия', $details->arrivalDate()?->format('d.m.Y')),
            new DetailOptionDto('Время прибытия',  $details->arrivalDate()?->format('H:i')),
            new DetailOptionDto('Номер поезда', $details->trainNumber()),
            new DetailOptionDto('Город прибытия', $details),
            new DetailOptionDto('Табличка для встречи', $details->meetingTablet()),
        ]);
    }

    private function buildIntercityTransfer(IntercityTransfer $details): Collection
    {
        return collect([
            new DetailOptionDto('Дата выезда', $details->departureDate()?->format('d.m.Y')),
            new DetailOptionDto('Время выезда', $details->departureDate()?->format('H:i')),
        ]);
    }

    private function buildDayCarTrip(DayCarTrip $details): Collection
    {
        return collect([
            new DetailOptionDto('Дата выезда', $details->date()?->format('d.m.Y')),
            new DetailOptionDto('Время выезда', $details->date()?->format('H:i')),
        ]);
    }

}
