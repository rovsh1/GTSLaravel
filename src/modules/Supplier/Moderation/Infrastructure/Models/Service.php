<?php

namespace Module\Supplier\Moderation\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\ServiceTypeEnum;

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
