<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\ValueObject\Options\Markup;

interface MarkupRepositoryInterface
{
    /**
     * @param int $hotelId
     * @return Markup
     */
    public function get(int $hotelId): Markup;

    public function update(int $hotelId, Markup $markup): void;
}
