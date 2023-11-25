<?php

namespace Module\Client\Moderation\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\Client\TypeEnum;
use Sdk\Shared\Enum\CurrencyEnum;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'city_id',
        'currency',
        'type',
        'residency',
        'status',
        'name',
        'description',
        'is_b2b',
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'type' => TypeEnum::class,
        'residency' => ResidencyEnum::class,
        'is_b2b' => 'boolean',
    ];
}
