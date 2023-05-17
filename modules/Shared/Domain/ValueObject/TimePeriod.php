<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class TimePeriod implements ValueObjectInterface, SerializableDataInterface
{
    private Time $from;
    private Time $to;

    public function __construct(string $from, string $to)
    {
        $fromTime = new Time($from);
        $toTime = new Time($to);
        $this->validateFromLessTo($fromTime, $toTime);
        $this->from = $fromTime;
        $this->to = $toTime;
    }

    public function from(): string
    {
        return $this->from->value();
    }

    public function to(): string
    {
        return $this->to->value();
    }

    public function setFrom(string $from): void
    {
        $time = new Time($from);
        $this->validateFromLessTo($time, $this->to);
        $this->from = $time;
    }

    public function setTo(string $to): void
    {
        $time = new Time($to);
        $this->validateFromLessTo($this->from, $time);
        $this->to = $time;
    }

    public function toData(): array
    {
        return [
            'from' => $this->from->value(),
            'to' => $this->to->value()
        ];
    }

    public static function fromData(array $data): static
    {
        return new static($data['from'], $data['to']);
    }

    /**
     * @param Time $from
     * @param Time $to
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateFromLessTo(Time $from, Time $to): void
    {
        if ($from > $to) {
            throw new \InvalidArgumentException("Invalid period values, from [{$from->value()}] to [{$to->value()}]");
        }
    }
}
