<?php

namespace Module\Catalog\Domain\Hotel\Factory;

use Carbon\CarbonPeriod;
use Module\Catalog\Domain\Hotel\Entity\Contract;
use Module\Catalog\Domain\Hotel\ValueObject\ContractId;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class ContractFactory extends AbstractEntityFactory
{
    protected string $entity = Contract::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new ContractId($data['id']),
            $data['number'],
            new CarbonPeriod($data['date_from'], $data['date_to'])
        );
    }
}
