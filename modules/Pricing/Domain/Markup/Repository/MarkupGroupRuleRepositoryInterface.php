<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\Repository;

use Module\Pricing\Domain\Markup\Entity\MarkupRule;
use Module\Pricing\Domain\Markup\ValueObject\MarkupGroupId;
use Module\Pricing\Domain\Markup\ValueObject\MarkupValue;

interface MarkupGroupRuleRepositoryInterface
{
    public function findRule(MarkupGroupId $groupId): MarkupRule;

    public function findHotelRoomRule(MarkupGroupId $groupId, int $roomId): ?MarkupValue;

    public function findClientRule(int $clientId): MarkupRule;
}
