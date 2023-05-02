<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class TimePeriod implements ValueObjectInterface
{
    public function __construct(
        private string $from,
        private string $to
    ) {}

    public function from(): string
    {
        return $this->from;
    }

    public function to(): string
    {
        return $this->to;
    }
}
