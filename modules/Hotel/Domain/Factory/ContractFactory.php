<?php

namespace Module\Hotel\Domain\Factory;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Entity\Contract;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class ContractFactory extends AbstractEntityFactory
{
    protected string $entity = Contract::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            $data['id'],
            $data['number'],
            new CarbonPeriod($data['date_from'], $data['date_to'])
        );
    }
}
