<?php

namespace Module\Booking\Order\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class Find implements QueryInterface
{
    public function __construct(public readonly int $id) {}
}
