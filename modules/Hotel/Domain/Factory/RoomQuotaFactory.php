<?php

namespace Module\Hotel\Domain\Factory;

use Custom\Framework\Support\DateTime;
use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Hotel\Domain\Entity\RoomQuota;

class RoomQuotaFactory extends AbstractEntityFactory
{
    protected string $entity = RoomQuota::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            $data['id'],
            $data['room_id'],
            new DateTime($data['date']),
            (bool)$data['status'],
            $data['release_days'],
            $data['count_total'],
            $data['count_available'],
            $data['count_booked'],
            $data['count_reserved'],
        );
    }
}
