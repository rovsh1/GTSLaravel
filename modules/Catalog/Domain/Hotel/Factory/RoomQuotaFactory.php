<?php

namespace Module\Catalog\Domain\Hotel\Factory;

use Module\Catalog\Domain\Hotel\Entity\RoomQuota;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Sdk\Module\Support\DateTime;

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
