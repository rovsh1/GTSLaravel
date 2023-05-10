<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class TimePeriod implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private string $from,
        private string $to
    ) {
        $this->from = $this->validateTime($this->from);
        $this->to = $this->validateTime($this->to);
        $this->validateFromLessTo();
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
        $this->from = $this->validateTime($from);
        $this->validateFromLessTo();
    }

    public function setTo(string $to): void
    {
        $this->to = $this->validateTime($to);
        $this->validateFromLessTo();
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

    private function validateTime(string $value): string
    {
        if (!preg_match('/^([0-1]?[0-9]|2[0-4]):[0-5][0-9]$/m', $value)) {
            throw new \InvalidArgumentException("Invalid value for period [{$value}]");
        }
        return $value;
    }

    private function validateFromLessTo(): void
    {
        if ($this->from > $this->to) {
            throw new \InvalidArgumentException("Invalid period values, from [{$this->from}] to [{$this->to}]");
        }
    }
}
