<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\Repository;

use Module\Pricing\Domain\Markup\Entity\MarkupRule;
use Module\Pricing\Domain\Markup\ValueObject\MarkupGroupId;

interface MarkupGroupRuleRepositoryInterface
{
    public function findRule(MarkupGroupId $groupId): MarkupRule;
}
