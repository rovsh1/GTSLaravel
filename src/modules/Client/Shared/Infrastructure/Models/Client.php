<?php

namespace Module\Client\Shared\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Client\LanguageEnum;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\Client\StatusEnum;
use Sdk\Shared\Enum\Client\TypeEnum;
use Sdk\Shared\Enum\CurrencyEnum;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'country_id',
        'currency',
        'is_b2b',
        'type',
        'status',
        'residency',
        'language',
        'description',
        'markup_group_id'
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'status' => StatusEnum::class,
        'type' => TypeEnum::class,
        'residency' => ResidencyEnum::class,
        'language' => LanguageEnum::class,
        'is_b2b' => 'boolean',
    ];
}
