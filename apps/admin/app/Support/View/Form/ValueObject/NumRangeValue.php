<?php

declare(strict_types=1);

namespace App\Admin\Support\View\Form\ValueObject;

class NumRangeValue
{
    public function __construct(
        public readonly ?int $from,
        public readonly ?int $to,
    ) {
    }
}
