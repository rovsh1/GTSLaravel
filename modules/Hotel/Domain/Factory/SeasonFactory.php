<?php

namespace Module\Hotel\Domain\Factory;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Entity\Season;
use Module\Shared\Domain\ValueObject\Id;
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
            new Id($data['id']),
            $data['name'],
            new CarbonPeriod($data['date_start'], $data['date_end']),
            $hotel,
            $contract
        );
    }
}
