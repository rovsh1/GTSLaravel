<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation\Room;

class TotalDto
{
    public function __construct(
        public readonly float $amountAfterTaxes
    ) {}
}
