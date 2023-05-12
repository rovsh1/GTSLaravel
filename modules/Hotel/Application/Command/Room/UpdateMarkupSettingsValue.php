<?php

namespace Module\Hotel\Application\Command\Room;

use Custom\Framework\Contracts\Bus\CommandInterface;

class UpdateMarkupSettingsValue implements CommandInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $key,
        public readonly mixed $value,
    ) {}
}
