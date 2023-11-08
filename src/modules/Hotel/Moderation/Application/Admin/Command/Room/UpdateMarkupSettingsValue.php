<?php

namespace Module\Hotel\Moderation\Application\Admin\Command\Room;

use Sdk\Module\Contracts\Bus\CommandInterface;

class UpdateMarkupSettingsValue implements CommandInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $key,
        public readonly mixed $value,
    ) {}
}
