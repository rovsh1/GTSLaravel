<?php

declare(strict_types=1);

namespace App\Admin\Repositories;

use App\Admin\Models\Administrator\Administrator;

class OrderAdministratorRepository
{
    public function update(int $orderId, int $administratorId): void
    {
        \DB::table('administrator_orders')->updateOrInsert(
            ['order_id' => $orderId],
            ['order_id' => $orderId, 'administrator_id' => $administratorId],
        );
    }

    public function get(int $orderId): ?Administrator
    {
        return Administrator::query()
            ->join('administrator_orders', 'administrators.id', '=', 'administrator_orders.administrator_id')
            ->where('administrator_orders.order_id', $orderId)
            ->first();
    }
}
