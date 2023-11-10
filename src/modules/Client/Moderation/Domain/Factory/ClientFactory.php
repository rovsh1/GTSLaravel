<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Factory;

use Module\Client\Moderation\Domain\Entity\Client;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Enum\Client\ResidencyEnum;
use Module\Shared\Enum\Client\TypeEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class ClientFactory extends AbstractEntityFactory
{
    protected string $entity = Client::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new ClientId($data['id']),
            $data['name'],
            TypeEnum::from($data['type']),
            ResidencyEnum::from($data['residency'])
        );
    }
}
