<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\ValueObject\Options\MarkupSettings;

interface MarkupRepositoryInterface
{
    /**
     * @param int $hotelId
     * @return MarkupSettings
     */
    public function get(int $hotelId): MarkupSettings;

    public function update(int $hotelId, MarkupSettings $markup): void;
}
