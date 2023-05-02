<?php

declare(strict_types=1);

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\PortGateway\Request;
use Module\Hotel\Application\Service\MarkupManager;

class AdditionalConditionsController
{
    public function __construct(
        private readonly MarkupManager $conditionsManager
    ) {}

    public function getAdditionalConditions(Request $request): array
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
        ]);

        return $this->conditionsManager->getAdditionalConditions($request->hotel_id);
    }
}
