<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\Entity\Room\RoomMarkups;

interface RoomMarkupSettingsRepositoryInterface
{
    public function get(int $id): ?RoomMarkups;

    public function update(RoomMarkups $markupSettings): bool;
}
