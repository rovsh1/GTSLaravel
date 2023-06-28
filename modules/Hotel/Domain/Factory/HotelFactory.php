<?php

namespace Module\Hotel\Domain\Factory;

use Module\Hotel\Domain\Entity\Hotel;
use Module\Hotel\Domain\ValueObject\TimeSettings;
use Module\Shared\Domain\Service\SerializerInterface;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class HotelFactory extends AbstractEntityFactory
{
    protected string $entity = Hotel::class;

    public function __construct(private readonly SerializerInterface $serializer)
    {
        parent::__construct();
    }

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new Id($data['id']),
            $data['name'],
            CurrencyEnum::fromId($data['currency_id']),
            $this->serializer->deserialize(TimeSettings::class, $data['time_settings'])
        );
    }
}
