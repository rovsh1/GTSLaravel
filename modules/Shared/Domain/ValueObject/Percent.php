<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class Percent implements ValueObjectInterface
{
    public function __construct(protected int $value)
    {
        $this->setValue($this->value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $this->validatePercent($value);
    }

    /**
     * @param int $percent
     * @return int
     * @throws \InvalidArgumentException
     */
    private function validatePercent(int $percent): int
    {
        if ($percent < 0 || $percent > 100) {
            throw new \InvalidArgumentException("Invalid percent value [{$percent}]");
        }
        return $percent;
    }
}
