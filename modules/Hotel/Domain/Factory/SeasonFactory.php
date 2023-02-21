<?php

namespace Module\Hotel\Domain\Factory;

use Carbon\CarbonPeriod;
use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Hotel\Domain\Entity\Season;

class SeasonFactory extends AbstractEntityFactory
{
    protected string $entity = Season::class;

    protected function fromArray(array $data): mixed
    {
        $hotel = array_key_exists('hotel', $data)
            ? app(HotelFactory::class)->createFrom($data['hotel'])
            : null;

        $contract = array_key_exists('contract', $data)
            ? app(ContractFactory::class)->createFrom($data['contract'])
            : null;

        return new $this->entity(
            $data['id'],
            $data['name'],
            new CarbonPeriod($data['date_from'], $data['date_to']),
            $hotel,
            $contract
        );
    }
}
