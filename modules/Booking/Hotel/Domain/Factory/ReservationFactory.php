<?php

namespace Module\Booking\Hotel\Domain\Factory;

use Carbon\Carbon;
use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Booking\Common\Domain\ValueObject\ClientTypeEnum;
use Module\Booking\Common\Domain\ValueObject\ReservationStatusEnum;
use Module\Booking\Hotel\Domain\Entity\Reservation;
use Module\Booking\Hotel\Domain\ValueObject\Client;
use Module\Booking\Hotel\Domain\ValueObject\Hotel;
use Module\Booking\Hotel\Domain\ValueObject\ReservationDetails;
use Module\Booking\Hotel\Domain\ValueObject\ReservationIdentifier;
use Module\Booking\Hotel\Domain\ValueObject\ReservationPeriod;
use Module\Booking\Hotel\Domain\ValueObject\ReservationStatus;

class ReservationFactory extends AbstractEntityFactory
{
    protected string $entity = Reservation::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
        //todo сейчас number = null во всей таблице
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
