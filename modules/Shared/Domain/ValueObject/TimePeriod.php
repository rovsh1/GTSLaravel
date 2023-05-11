<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class TimePeriod implements ValueObjectInterface, SerializableDataInterface
{
    private string $from;
    private string $to;

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

    public function setFrom(string $from): void
    {
        $this->validateTime($from);
        $this->validateFromLessTo($from, $this->to);
        $this->from = $from;
    }

    public function setTo(string $to): void
    {
        $this->validateTime($to);
        $this->validateFromLessTo($this->from, $to);
        $this->to = $to;
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

    /**
     * @param string $value
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateTime(string $value): void
    {
        if (!preg_match('/^([0-1]?[0-9]|2[0-4]):[0-5][0-9]$/m', $value)) {
            throw new \InvalidArgumentException("Invalid value for period [{$value}]");
        }
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
}
