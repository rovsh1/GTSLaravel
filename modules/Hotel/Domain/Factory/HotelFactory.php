<?php

namespace Module\Hotel\Domain\Factory;

use Module\Hotel\Domain\Entity\Hotel;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new Id($data['id']),
            $data['name']
        );
    }
}
