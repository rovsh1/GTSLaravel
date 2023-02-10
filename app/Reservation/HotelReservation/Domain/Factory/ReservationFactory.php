<?php

namespace GTS\Reservation\HotelReservation\Domain\Factory;

use Carbon\Carbon;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;

use GTS\Reservation\Common\Domain\ValueObject\ClientTypeEnum;
use GTS\Reservation\HotelReservation\Domain\Entity\Reservation;
use GTS\Reservation\HotelReservation\Domain\ValueObject\Client;
use GTS\Reservation\HotelReservation\Domain\ValueObject\Hotel;
use GTS\Reservation\HotelReservation\Domain\ValueObject\ReservationDetails;
use GTS\Reservation\HotelReservation\Domain\ValueObject\ReservationIdentifier;
use GTS\Reservation\HotelReservation\Domain\ValueObject\ReservationPeriod;
use GTS\Reservation\HotelReservation\Domain\ValueObject\ReservationStatus;

class ReservationFactory extends AbstractEntityFactory
{
    protected string $entity = Reservation::class;

    protected function fromArray(array $data): mixed
    {
        return new Reservation(
            //@todo сейчас number = null во всей таблице
            new ReservationIdentifier($data['id'], $data['number']),
            new ReservationStatus(
                null,
            ),
            new Client(
                $data['client_id'],
                ClientTypeEnum::from($data['client_type'])
            ),
            new Hotel($data['hotel_id']),
            new ReservationPeriod(
                new Carbon($data['date_checkin']),
                new Carbon($data['date_checkout']),
                $data['nights_number'],
            ),
            new ReservationDetails(
                $data['note']
            )
        );
    }
}
