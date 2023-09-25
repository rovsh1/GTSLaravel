<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

use Module\Shared\Contracts\CanEquate;

class TimePeriod implements ValueObjectInterface, SerializableDataInterface, CanEquate
{
    private readonly string $from;
    private readonly string $to;

    public function __construct(string $from, string $to)
    {
        $this->validateTime($from);
        $this->validateTime($to);
        $this->validateFromLessTo($from, $to);
        $this->from = $from;
        $this->to = $to;
    }

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
            'to' => $this->to,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static($data['from'], $data['to']);
    }

    /**
     * @param string $from
     * @param string $to
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateFromLessTo(string $from, string $to): void
    {
        if ($from > $to) {
            throw new \InvalidArgumentException("Invalid period values, from [{$from}] to [{$to}]");
        }
    }

    /**
     * @param string $value
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateTime(string $value): void
    {
        if (!preg_match('/^([0-1]?[0-9]|2[0-4]):[0-5][0-9]$/m', $value)) {
            throw new \InvalidArgumentException("Invalid time value [{$value}]");
        }
    }

    public function isEqual(mixed $b): bool
    {
        return $b instanceof TimePeriod
            ? $this->from === $b->from && $this->to === $b->to
            : $this === $b;
    }
}
