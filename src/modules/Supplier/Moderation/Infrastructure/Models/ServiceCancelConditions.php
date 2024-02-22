<?php

namespace Module\Supplier\Moderation\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class ServiceCancelConditions extends Model
{
    public $timestamps = false;

    protected $table = 'supplier_service_cancel_conditions';

    protected $fillable = [
        'season_id',
        'service_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
