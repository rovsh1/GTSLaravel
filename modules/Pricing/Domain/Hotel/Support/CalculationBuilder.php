<?php

namespace Module\Pricing\Domain\Hotel\Support;

use Module\Pricing\Domain\Hotel\Dto\CalculationDto;

class CalculationBuilder
{
    private array $sumValues;

    private float $lastValue;

    private string $formula = '';

    public function resultValue(): float
    {
        return array_sum($this->sumValues);
    }

    public function base(float $value, string $name): static
    {
        $this->sumValues = [$value];

        return $this->add($value, $name, '+');
    }

    public function plus(float $value, string $name): static
    {
        $this->sumValues[] = $value;

        return $this->add($value, $name, '+');
    }

    public function multiply(float $value, string $name): static
    {
        $this->sumValues[count($this->sumValues) - 1] = $this->lastValue * $value;

        return $this->add($value, $name, '*');
    }

    public function build(): CalculationDto
    {
        return new CalculationDto($this->resultValue(), $this->formula);
    }

    private function add(float $value, string $name, string $sign): static
    {
        $this->lastValue = $value;
        $this->formula .= (empty($this->formula) ? '' : ' ' . $sign . ' ')
            . self::formatNumber($value)
            . '(' . $name . ')';

        return $this;
    }

    private static function formatNumber(float $value): string
    {
        return (string)round($value);
    }
}