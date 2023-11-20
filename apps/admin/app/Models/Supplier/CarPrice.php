<?php

declare(strict_types=1);

namespace App\Admin\Models\Supplier;

use Module\Supplier\Moderation\Infrastructure\Models\TransferServicePrice;

class CarPrice extends TransferServicePrice
{
    protected $fillable = [
        'service_id',
        'season_id',
        'car_id',
        'currency',
        'price_net',
        'prices_gross',
    ];
}
