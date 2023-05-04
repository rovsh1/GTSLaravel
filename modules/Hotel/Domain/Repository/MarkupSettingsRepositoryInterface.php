<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\ValueObject\MarkupSettings\MarkupSettings;

interface MarkupSettingsRepositoryInterface
{
    public function get(int $hotelId): MarkupSettings;

    public function updateClientMarkups(int $hotelId, MarkupSettings $markupSettings): bool;
}
