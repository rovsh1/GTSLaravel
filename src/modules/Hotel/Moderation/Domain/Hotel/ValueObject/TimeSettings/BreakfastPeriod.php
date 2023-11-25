<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject\TimeSettings;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Sdk\Shared\Contracts\Support\SerializableInterface;

class BreakfastPeriod implements ValueObjectInterface, SerializableInterface
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

    public function serialize(): array
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static($payload['from'], $payload['to']);
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
}
