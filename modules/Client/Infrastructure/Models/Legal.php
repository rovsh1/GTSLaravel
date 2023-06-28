<?php

namespace Module\Client\Infrastructure\Models;

use Module\Shared\Enum\Client\LegalTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Legal extends Model
{
    protected $table = 'client_legals';

    protected $fillable = [
        'name',
        'client_id',
        'city_id',
        'industry_id',
        'type',
        'address',
        'requisites',
    ];

    protected $casts = [
        'type' => LegalTypeEnum::class,
    ];
}
