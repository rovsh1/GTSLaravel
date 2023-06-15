<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Models;

use Module\Shared\Enum\Booking\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service_provider_services';

    protected $fillable = [
        'provider_id',
        'name',
        'type',
    ];

    protected $casts = [
        'provider_id' => 'int',
        'type' => ServiceTypeEnum::class,
    ];
}
