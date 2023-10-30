<?php

namespace Module\Pricing\Infrastructure\Models;

use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'supplier_services';

    protected $fillable = [
        'supplier_id',
        'title',
        'type',
        'data',
    ];

    protected $casts = [
        'type' => ServiceTypeEnum::class,
        'data' => 'array'
    ];
}
