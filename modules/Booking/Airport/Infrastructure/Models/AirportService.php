<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Models;

use Module\Shared\Enum\Booking\AirportServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class AirportService extends Model
{
    protected $table = 'supplier_airport_services';

    protected $fillable = [
        'provider_id',
        'name',
        'type',
    ];

    protected $casts = [
        'provider_id' => 'int',
        'type' => AirportServiceTypeEnum::class,
    ];
}
