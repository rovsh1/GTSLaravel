<?php

declare(strict_types=1);

namespace Module\Shared\Domain\Service;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

interface DomainSerializerInterface
{
    public function serialize(SerializableDataInterface $object): string;

    /**
     * @param class-string<SerializableDataInterface> $object
     * @param string $serializedData
     * @return SerializableDataInterface
     */
    public function deserialize(string $object, string $serializedData): SerializableDataInterface;
}
