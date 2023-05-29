<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation\Room;

use Sdk\Module\Foundation\Support\Dto\Dto;

class TotalDto extends Dto
{
    public function __construct(
        public readonly float $amountAfterTaxes
    ) {}
}
