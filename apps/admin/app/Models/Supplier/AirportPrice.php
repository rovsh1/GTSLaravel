<?php

declare(strict_types=1);

namespace App\Admin\Models\Supplier;

use Sdk\Module\Database\Eloquent\Model;

class AirportPrice extends Model
{
    protected $table = 'supplier_airport_prices';

    public $timestamps = false;

    protected $fillable = [
        'service_id',
        'season_id',
        'airport_id',
        'currency_id',
        'price_net',
        'prices_gross',
    ];

    protected $casts = [
        'price_net' => 'float',
        'prices_gross' => 'array',
    ];
}
