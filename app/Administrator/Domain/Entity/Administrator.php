<?php

namespace GTS\Administrator\Domain\Entity;

class Administrator
{
    public function __construct(
        private readonly int $id,
        private string $presentation,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function presentation(): string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): void
    {
        $this->presentation = $presentation;
    }
}
