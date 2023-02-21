<?php

namespace App\Core\Helpers\Price;

use function GTS\Shared\UI\Common\Helpers\Price\app;
use function GTS\Shared\UI\Common\Helpers\Price\lang;

class PriceDecorator
{
    public function __construct(
        public readonly ?float $value = null,
        public readonly ?string $currency = null
    ) {}

    public function format($format = 'price', bool $withCurrency = true): string
    {
        return app('format')->number($this->value, $format)
            . ($withCurrency && $this->currency ? ' ' . $this->currency : '');
    }

    public function toHtml(): string
    {
        return '<span class="ui-price">'
            . '<span class="price-value">' . app('format')->number($this->value, 'price') . '</span>'
            . ' <span class="price-currency">' . ($this->currency ? $this->currency->sign : lang('currency')) . '</span>'
            . '</span>';
    }

    public function toNumber(int $precision = 0): float
    {
        return round($this->price, $precision);
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
