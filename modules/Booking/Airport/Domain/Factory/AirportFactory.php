<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Factory;

use Module\Booking\Airport\Domain\Entity\Airport;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class AirportFactory extends AbstractEntityFactory
{
    /** @var class-string<Airport> */
    protected string $entity = Airport::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new Id($data['id']),
            $data['name']
        );
    }
}
