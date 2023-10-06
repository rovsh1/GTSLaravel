<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Repository;

use Module\Pricing\Domain\Markup\Entity\MarkupRule;
use Module\Pricing\Domain\Markup\Repository\MarkupGroupRuleRepositoryInterface;
use Module\Pricing\Domain\Markup\ValueObject\MarkupGroupId;
use Module\Pricing\Infrastructure\Models\MarkupGroup;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class MarkupGroupRuleRepository implements MarkupGroupRuleRepositoryInterface
{
    public function findRule(MarkupGroupId $groupId): MarkupRule
    {
        $group = MarkupGroup::find($groupId->value());
        if ($group === null) {
            throw new EntityNotFoundException('Markup group not found');
        }

    }
}
