<?php

namespace Module\Reservation\HotelReservation\Domain\Factory;

use Carbon\Carbon;
use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Reservation\Common\Domain\ValueObject\ClientTypeEnum;
use Module\Reservation\Common\Domain\ValueObject\ReservationStatusEnum;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation;
use Module\Reservation\HotelReservation\Domain\ValueObject\Client;
use Module\Reservation\HotelReservation\Domain\ValueObject\Hotel;
use Module\Reservation\HotelReservation\Domain\ValueObject\ReservationDetails;
use Module\Reservation\HotelReservation\Domain\ValueObject\ReservationIdentifier;
use Module\Reservation\HotelReservation\Domain\ValueObject\ReservationPeriod;
use Module\Reservation\HotelReservation\Domain\ValueObject\ReservationStatus;

class ReservationFactory extends AbstractEntityFactory
{
    protected string $entity = Reservation::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
        //@todo сейчас number = null во всей таблице
            new ReservationIdentifier($data['id'], $data['number'] ?? ''),
            new ReservationStatus(
                ReservationStatusEnum::from($data['status']),
            ),
            new Client(
                $data['client_id'],
                ClientTypeEnum::from($data['client_type']),
                $data['client_name'],
            ),
            new Hotel($data['hotel_id']),
            new ReservationPeriod(
                new Carbon($data['date_checkin']),
                new Carbon($data['date_checkout']),
                $data['nights_number'],
            ),
            new ReservationDetails(
                $data['note']
            ),
            new Carbon($data['created']),
        );
    }
}
