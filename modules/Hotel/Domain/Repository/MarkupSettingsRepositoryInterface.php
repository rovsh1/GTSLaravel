<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\Entity\MarkupSettings;

interface MarkupSettingsRepositoryInterface
{
    public function get(int $id): MarkupSettings;

    public function update(MarkupSettings $markupSettings): bool;
}
