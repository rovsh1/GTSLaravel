<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Service;

use Module\Shared\Domain\Service\DomainSerializerInterface;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

class JsonSerializer implements DomainSerializerInterface
{
    public function serialize(SerializableDataInterface $object): string
    {
        return json_encode($object->toData());
    }

    /**
     * @param class-string<SerializableDataInterface> $object
     * @param string $serializedData
     * @return SerializableDataInterface
     */
    public function deserialize(string $object, string $serializedData): SerializableDataInterface
    {
        return $object::fromData(json_decode($serializedData, true));
    }
}
