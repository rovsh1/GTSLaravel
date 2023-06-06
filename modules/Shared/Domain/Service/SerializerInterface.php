<?php

declare(strict_types=1);

namespace Module\Shared\Domain\Service;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

interface SerializerInterface
{
    public function serialize(SerializableDataInterface $object): string;

    /**
     * @template T
     * @param class-string<T> $object
     * @param string $serializedData
     * @return T
     */
    public function deserialize(string $object, string $serializedData): SerializableDataInterface;
}
