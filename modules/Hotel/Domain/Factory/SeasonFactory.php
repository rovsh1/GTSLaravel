<?php

namespace Module\Hotel\Domain\Factory;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Entity\Season;
use Module\Hotel\Domain\ValueObject\ContractId;
use Module\Hotel\Domain\ValueObject\HotelId;
use Module\Hotel\Domain\ValueObject\SeasonId;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class SeasonFactory extends AbstractEntityFactory
{
    protected string $entity = Season::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new SeasonId($data['id']),
            $data['name'],
            new CarbonPeriod($data['date_start'], $data['date_end']),
            new HotelId($data['hotel_id']),
            new ContractId($data['contract_id'])
        );
    }
}
