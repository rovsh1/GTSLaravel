<?php

declare(strict_types=1);

namespace App\Admin\Models\Supplier;

use Module\Supplier\Moderation\Infrastructure\Models\OtherServicePrice;

class OtherPrice extends OtherServicePrice
{
    protected $fillable = [
        'service_id',
        'season_id',
        'currency',
        'price_net',
        'prices_gross',
    ];
}
