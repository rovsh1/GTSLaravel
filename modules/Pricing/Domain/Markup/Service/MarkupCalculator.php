<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\Service;

use Module\Pricing\Domain\Markup\Repository\MarkupGroupRuleRepositoryInterface;

class MarkupCalculator
{
    public function __construct(
        private readonly MarkupGroupRuleRepositoryInterface $markupGroupRuleRepository,
    ) {}

    public function calculate(float $price, int $clientId, int $roomId): float {}

    private function calculateClientMarkup(int $roomId, int $clientId): int
    {
        $clientMarkupRule = $this->markupGroupRuleRepository->findClientRule($clientId);

        $roomMarkupValue = $this->markupGroupRuleRepository->findHotelRoomRule($clientMarkupRule->groupId(), $roomId);
        if ($roomMarkupValue !== null) {
            return 0;
        }

        return 0;
    }
}
