<?php

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class SetSeasonPrice implements CommandInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly array $sortedRoomsIds,
    ) {}
}