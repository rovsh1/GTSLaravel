<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Service;

use Module\Shared\Contracts\Service\SerializerInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

class JsonSerializer implements SerializerInterface
{
    public function serialize(SerializableDataInterface $object): string
    {
        return json_encode($object->toData());
    }

    /**
     * @template T
     * @param class-string<T> $object
     * @param string $serializedData
     * @return T
     */
    public function deserialize(string $object, string $serializedData): SerializableDataInterface
    {
        return $object::fromData(json_decode($serializedData, true));
    }
}
