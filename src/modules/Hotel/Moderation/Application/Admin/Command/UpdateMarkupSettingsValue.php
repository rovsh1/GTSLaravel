<?php

namespace Module\Hotel\Moderation\Application\Admin\Command;

use Module\Hotel\Moderation\Application\Admin\Enums\UpdateMarkupSettingsActionEnum;
use Sdk\Module\Contracts\Bus\CommandInterface;

class UpdateMarkupSettingsValue implements CommandInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $key,
        public readonly mixed $value,
        public readonly UpdateMarkupSettingsActionEnum $action
    ) {}
}
