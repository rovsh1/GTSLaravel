<?php

declare(strict_types=1);

namespace App\Admin\Repositories;

use App\Admin\Models\Administrator\Administrator;

class OrderAdministratorRepository
{
    public function get(int $orderId): ?Administrator
    {
        return Administrator::query()
            ->join('administrator_orders', 'administrators.id', '=', 'administrator_orders.administrator_id')
            ->where('administrator_orders.order_id', $orderId)
            ->first();
    }
}
