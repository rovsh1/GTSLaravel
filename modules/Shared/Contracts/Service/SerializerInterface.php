<?php

declare(strict_types=1);

namespace Module\Shared\Contracts\Service;

use Module\Shared\Contracts\Support\SerializableDataInterface;

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
