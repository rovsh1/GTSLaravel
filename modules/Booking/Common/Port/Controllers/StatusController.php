<?php

declare(strict_types=1);

namespace Module\Booking\Common\Port\Controllers;

use Illuminate\Validation\Rules\Enum;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\PortGateway\Request;

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

    public function updateStatus(Request $request): void
    {
        $request->validate([
            'id' => ['required', 'int'],
            'status' => ['required', new Enum(BookingStatusEnum::class)]
        ]);
    }
}
