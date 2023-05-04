<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class UpdateClientMarkups implements CommandInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly int|null $individual,
        public readonly int|null $OTA,
        public readonly int|null $TA,
        public readonly int|null $TO
    ) {}
}
