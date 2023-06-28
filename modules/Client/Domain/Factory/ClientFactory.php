<?php

declare(strict_types=1);

namespace Module\Client\Domain\Factory;

use Module\Client\Domain\Entity\Client;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\Client\TypeEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class ClientFactory extends AbstractEntityFactory
{
    protected string $entity = Client::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new Id($data['id']),
            $data['name'],
            TypeEnum::from($data['type']),
        );
    }
}
