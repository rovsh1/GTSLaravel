<?php

namespace Module\HotelOld\Domain\Factory;

use Carbon\CarbonPeriod;
use Module\HotelOld\Domain\Entity\Season;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

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
