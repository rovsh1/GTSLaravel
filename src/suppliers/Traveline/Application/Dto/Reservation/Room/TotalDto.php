<?php

namespace Supplier\Traveline\Application\Dto\Reservation\Room;

class TotalDto
{
    public function __construct(
        public readonly float $amountAfterTaxes
    ) {}
}
