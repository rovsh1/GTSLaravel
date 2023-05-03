<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class TimePeriod implements ValueObjectInterface, SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'from' => $this->from,
            'to' => $this->to
        ];
    }

    public static function fromData(array $data): static
    {
        return new static($data['from'], $data['to']);
    }
}
