<?php

declare(strict_types=1);

namespace Module\Booking\Common\Port\Controllers;

use Custom\Framework\PortGateway\Request;

class StatusController
{
    public function getStatuses(Request $request): array
    {
        return [
            ['id' => 1, 'name' => 'Новый'],
            ['id' => 2, 'name' => 'В работе'],
            ['id' => 3, 'name' => 'Отменен'],
        ];
    }

    public function getAvailableStatuses(Request $request): array
    {
        $request->validate([
            'id' => ['required', 'int']
        ]);

        return [
            ['id' => 1, 'name' => 'Новый'],
            ['id' => 2, 'name' => 'В работе'],
            ['id' => 3, 'name' => 'Отменен'],
        ];
    }
}
