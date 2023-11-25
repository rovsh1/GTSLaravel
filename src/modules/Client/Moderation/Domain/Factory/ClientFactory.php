<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Factory;

use Module\Client\Moderation\Domain\Entity\Client;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\Client\TypeEnum;

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
