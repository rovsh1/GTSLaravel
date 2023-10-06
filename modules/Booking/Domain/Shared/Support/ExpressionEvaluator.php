<?php

namespace Module\Booking\Domain\Shared\Support;

use Module\Booking\Domain\HotelBooking\Service\PriceCalculator\CalculationResult;

class ExpressionEvaluator
{
    private readonly string $expression;

    private array $variables = [];

    public function __construct(
        string $expression
    ) {
        $this->validateExpression($expression);

        $this->expression = $expression;
    }

    public function addVariable(string $key, int|float $value, string $note): static
    {
        $this->variables[$key] = [$value, $note];

        return $this;
    }

    public function evaluate(): CalculationResult
    {
        $formula = $this->expression;
        $expression = $this->expression;
        foreach ($this->variables as $k => $v) {
            $formula = str_replace($k, self::numberFormat($v[0]) . ' (' . $v[1] . ')', $formula);
            $expression = str_replace($k, $v[0], $expression);
        }
        $result = null;
        eval('$result=' . $expression . ';');

        return new CalculationResult(
            (float)$result,
            $formula
        );
    }

    private static function numberFormat(int|float $value): string
    {
        return number_format($value, 0, '.', ' ');
    }

    private function validateExpression(string $expression): void
    {
        if (preg_match('/[^0-9a-z()\-+* \/]/i', $expression)) {
            throw new \LogicException('Unsafe formula expression');
        }
    }
}
