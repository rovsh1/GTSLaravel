<?php

declare(strict_types=1);

namespace Module\Catalog\Domain\Hotel\Repository;

use Module\Catalog\Domain\Hotel\Entity\MarkupSettings;

interface MarkupSettingsRepositoryInterface
{
    public function get(int $id): MarkupSettings;

    public function update(MarkupSettings $markupSettings): bool;
}
