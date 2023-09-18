<?php

namespace Module\Support\FileStorage\Domain\Entity;

use Module\Support\FileStorage\Domain\ValueObject\Guid;

final class File
{
    public function __construct(
        private readonly Guid $guid,
        private string $name,
    ) {
    }

    public function guid(): Guid
    {
        return $this->guid;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function serialize(): array
    {
        return [
            'guid' => $this->guid->value(),
            'name' => $this->name
        ];
    }

    public static function deserialize(array $payload): File
    {
        return new File(
            new Guid($payload['guid']),
            $payload['name']
        );
    }
}
