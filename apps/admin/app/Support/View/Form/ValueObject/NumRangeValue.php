<?php

declare(strict_types=1);

namespace App\Admin\Support\View\Form\ValueObject;

class NumRangeValue
{
    public readonly int $from;
    public readonly int $to;

    public function __construct(?int $from, ?int $to) {
        $this->from = $from ?? 0;
        $this->to = $to ?? 10;
    }
}
