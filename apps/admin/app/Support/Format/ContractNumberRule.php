<?php

namespace App\Admin\Support\Format;

use Gsdk\Format\Rules\RuleInterface;

class ContractNumberRule implements RuleInterface
{
    public function format($value, $format = null): string
    {
        return 'Договор №' . str_pad($value, 6, '0', STR_PAD_LEFT);
    }
}
