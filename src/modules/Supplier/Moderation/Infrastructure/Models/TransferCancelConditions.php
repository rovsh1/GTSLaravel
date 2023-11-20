<?php

namespace Module\Supplier\Moderation\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class TransferCancelConditions extends Model
{
    public $timestamps = false;

    protected $table = 'supplier_car_cancel_conditions';

    protected $fillable = [
        'season_id',
        'service_id',
        'car_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
