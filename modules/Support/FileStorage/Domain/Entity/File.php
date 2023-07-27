<?php

namespace Module\Support\FileStorage\Domain\Entity;

class File
{
    public function __construct(
        private readonly string $guid,
        private readonly string $type,
        private readonly ?string $extension,
        private readonly ?int $entityId,
        private ?string $name,
    ) {}

    public function guid(): string
    {
        return $this->guid;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function extension(): ?string
    {
        return $this->extension;
    }

    public function entityId(): ?int
    {
        return $this->entityId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}