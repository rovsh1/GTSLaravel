<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\MarkupSettings;

interface MarkupSettingsRepositoryInterface
{
    public function get(int $id): MarkupSettings;

    public function update(MarkupSettings $markupSettings): bool;
}
