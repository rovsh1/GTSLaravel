<?php

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Application\Enums\UpdateMarkupSettingsActionEnum;

class UpdateMarkupSettingsValue implements CommandInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $key,
        public readonly mixed $value,
        public readonly UpdateMarkupSettingsActionEnum $action
    ) {}
}
