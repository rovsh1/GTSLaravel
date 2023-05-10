<?php

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Entity\MarkupSettings;

class UpdateMarkupSettingsValue implements CommandInterface
{
    public function __construct(
        int $id,
        MarkupSettings $markupSettings
    ) {}
}
