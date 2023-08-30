<?php

namespace Module\Client\Infrastructure\Models;

use Module\Shared\Enum\Client\ResidencyEnum;
use Module\Shared\Enum\Client\TypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'city_id',
        'currency_id',
        'type',
        'residency',
        'status',
        'name',
        'description',
        'is_b2b',
    ];

    protected $casts = [
        'type' => TypeEnum::class,
        'residency' => ResidencyEnum::class,
        'is_b2b' => 'boolean',
    ];
}
