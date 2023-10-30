<?php

declare(strict_types=1);

namespace Module\Shared\Contracts\Support;

interface SerializableDataInterface
{
    public function toData(): array;

    public static function fromData(array $data): static;
}
