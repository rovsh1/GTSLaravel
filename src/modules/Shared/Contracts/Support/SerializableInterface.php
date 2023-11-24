<?php

namespace Module\Shared\Contracts\Support;

interface SerializableInterface
{
    public function serialize(): array;

    public static function deserialize(array $payload): SerializableInterface;
}