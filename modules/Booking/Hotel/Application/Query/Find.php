<?php

namespace Module\Booking\Hotel\Application\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class Find implements QueryInterface
{
    public function __construct(public readonly int $id) {}
}
