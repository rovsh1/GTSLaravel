<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\Entity\Room\MarkupSettings;

interface RoomMarkupSettingsRepositoryInterface
{
    public function get(int $id): MarkupSettings;

    public function update(MarkupSettings $markupSettings): bool;
}
