<?php

namespace GTS\Hotel\Domain\Factory;

use Carbon\Carbon;
use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use GTS\Hotel\Domain\Entity\Room;

class QuotaFactory extends AbstractEntityFactory
{
    protected string $entity = Room\Quota::class;

    protected function fromArray(array $data): mixed
    {
        return new Room\Quota(
            $data['room'],
            new Carbon($data['date']),
            $data['count_available'],
            $data['count_booked'],
            $data['period'],
        );
    }
}
