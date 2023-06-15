<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Factory;

use Module\Booking\Airport\Domain\Entity\Service;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\Booking\ServiceTypeEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class ServiceFactory extends AbstractEntityFactory
{
    protected string $entity = Service::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new Id($data['id']),
            $data['name'],
            ServiceTypeEnum::from($data['type'])
        );
    }
}
