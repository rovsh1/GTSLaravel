<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\Room\RoomMarkups;

interface RoomMarkupSettingsRepositoryInterface
{
    public function get(int $id): ?RoomMarkups;

    public function update(RoomMarkups $markupSettings): bool;
}
