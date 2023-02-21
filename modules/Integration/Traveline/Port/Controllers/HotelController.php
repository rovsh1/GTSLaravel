<?php

namespace Module\Integration\Traveline\Port\Controllers;

use Custom\Framework\Port\Request;

class HotelController
{
    public function update(Request $request)
    {
        $request->validate([
            'hotel_id' => '',
            'updates' => ''
        ]);

        //@todo вызов команд/сервисов/запросов
        //@todo конвертация в DTO
    }

    public function getRoomsAndRatePlans(Request $request): array
    {
        $request->validate([
            'hotel_id' => '',
        ]);

        return [];
    }

}
