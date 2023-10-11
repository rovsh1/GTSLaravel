<?php

declare(strict_types=1);

namespace App\Admin\Models\Supplier;

use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Database\Eloquent\Model;

class CarPrice extends Model
{
    protected $table = 'supplier_car_prices';

    public $timestamps = false;

    protected $fillable = [
        'service_id',
        'season_id',
        'car_id',
        'currency',
        'price_net',
        'prices_gross',
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'price_net' => 'float',
        'prices_gross' => 'array',
    ];
}
