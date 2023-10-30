<?php

namespace Module\Shared\Contracts\Support;

interface Serializable
{
    public function serialize(): array;

    public static function deserialize(array $payload): Serializable;
}