<?php

declare(strict_types=1);

namespace App\Admin\Models\ServiceProvider;

use Sdk\Module\Database\Eloquent\Model;

class AirportPrice extends Model
{
    protected $table = 'service_provider_airport_prices';

    public $timestamps = false;

    protected $fillable = [
        'service_id',
        'season_id',
        'airport_id',
        'currency_id',
        'price_net',
        'price_gross',
    ];

    protected $casts = [
        'price_net' => 'float',
        'price_gross' => 'float',
    ];
}
